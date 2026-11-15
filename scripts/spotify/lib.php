<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

final class PrivateStore
{
    public string $directory;
    public function __construct(string $directory)
    {
        if (!preg_match('~^(?:[A-Za-z]:[\\\\/]|/)~', $directory)) {
            throw new RuntimeException('Le dossier privé doit être un chemin absolu.');
        }
        if (!is_dir($directory) && !mkdir($directory, 0700, true)) {
            throw new RuntimeException('Impossible de créer le dossier privé.');
        }
        $resolved = realpath($directory);
        $root = realpath(dirname(__DIR__, 2));
        $normalize = static fn(string $p): string => strtolower(str_replace('\\', '/', $p));
        if (!$resolved || !$root || str_starts_with($normalize($resolved) . '/', $normalize($root) . '/')) {
            throw new RuntimeException('Les jetons doivent rester hors du dossier du site.');
        }
        $this->directory = $resolved;
        if (DIRECTORY_SEPARATOR === '/') { chmod($resolved, 0700); }
    }
    public function read(string $name): array
    {
        $path = $this->directory . '/' . $name . '.json';
        if (!is_file($path)) { return []; }
        return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }
    public function write(string $name, array $data): void
    {
        $path = $this->directory . '/' . $name . '.json';
        $temp = tempnam($this->directory, '.write-');
        try {
            if ($temp === false || file_put_contents($temp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)) === false) {
                throw new RuntimeException('Écriture privée impossible.');
            }
            if (DIRECTORY_SEPARATOR === '/') { chmod($temp, 0600); }
            if (!rename($temp, $path)) { throw new RuntimeException('Remplacement du fichier privé impossible.'); }
        } finally {
            if ($temp && is_file($temp)) { unlink($temp); }
        }
    }
    public function lock()
    {
        $handle = fopen($this->directory . '/sync.lock', 'c');
        if (!$handle || !flock($handle, LOCK_EX | LOCK_NB)) {
            throw new RuntimeException('Une autre synchronisation ou connexion est déjà en cours.');
        }
        return $handle;
    }
}

// Never include response bodies in errors: OAuth responses may contain credentials.
function spotifyHttp(string $method, string $url, array $headers, ?string $body): array
{
    if (!extension_loaded('curl')) { throw new RuntimeException('PHP cURL est requis.'); }
    $retryAfter = 0;
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => $method, CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers, CURLOPT_CONNECTTIMEOUT => 15, CURLOPT_TIMEOUT => 45,
        CURLOPT_FOLLOWLOCATION => false, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_HEADERFUNCTION => static function ($ch, string $line) use (&$retryAfter): int {
            if (stripos($line, 'Retry-After:') === 0) { $retryAfter = (int) trim(substr($line, 12)); }
            return strlen($line);
        },
    ]);
    // Windows PHP does not ship a CA bundle. Use the OS trust store, keeping TLS verification enabled.
    if (PHP_OS_FAMILY === 'Windows' && defined('CURLSSLOPT_NATIVE_CA')) {
        curl_setopt($curl, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
    }
    if ($body !== null) { curl_setopt($curl, CURLOPT_POSTFIELDS, $body); }
    $raw = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $curlError = curl_errno($curl);
    curl_close($curl);
    if ($raw === false) { throw new RuntimeException('Connexion Spotify interrompue (cURL ' . $curlError . '). Relancer pour relire la playlist avant tout nouvel ajout.'); }
    $json = json_decode($raw, true);
    return ['status' => $status, 'data' => is_array($json) ? $json : [], 'retry_after' => $retryAfter];
}

interface SpotifyGateway
{
    public function request(string $method, string $path, ?array $body = null): array;
}

final class SpotifyClient implements SpotifyGateway
{
    private array $tokens;
    private $transport;
    public function __construct(private PrivateStore $store, ?callable $transport = null)
    {
        $this->tokens = $store->read('credentials');
        $this->transport = $transport ?? 'spotifyHttp';
        if (empty($this->tokens['refresh_token']) || empty($this->tokens['client_id'])) {
            throw new RuntimeException('Spotify non connecté. Exécuter la commande authorize.');
        }
    }
    private function refresh(): void
    {
        $result = ($this->transport)('POST', 'https://accounts.spotify.com/api/token', ['Content-Type: application/x-www-form-urlencoded'], http_build_query([
            'grant_type' => 'refresh_token', 'refresh_token' => $this->tokens['refresh_token'], 'client_id' => $this->tokens['client_id'],
        ]));
        if ($result['status'] === 400 && ($result['data']['error'] ?? '') === 'invalid_grant') {
            $this->store->write('credentials', ['client_id' => $this->tokens['client_id'], 'reauthorization_required' => true]);
            throw new RuntimeException('Autorisation Spotify expirée ou révoquée. Relancer authorize puis transférer la nouvelle connexion privée.');
        }
        if ($result['status'] !== 200 || empty($result['data']['access_token'])) {
            throw new RuntimeException('Renouvellement Spotify refusé (HTTP ' . $result['status'] . '). Reconnecter le compte si nécessaire.');
        }
        $this->tokens = array_merge($this->tokens, $result['data'], ['expires_at' => time() + (int) $result['data']['expires_in']]);
        $this->store->write('credentials', $this->tokens);
    }
    public function request(string $method, string $path, ?array $body = null): array
    {
        if (str_starts_with($path, 'https://api.spotify.com/v1/')) { $path = substr($path, strlen('https://api.spotify.com/v1')); }
        if (!preg_match('~^/(?:me|artists|albums|tracks|playlists)(?:/|\?|$)~', $path) || str_contains($path, '..')) {
            throw new RuntimeException('Chemin Spotify refusé.');
        }
        if (($this->tokens['expires_at'] ?? 0) < time() + 60) { $this->refresh(); }
        $refreshed = false;
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $result = ($this->transport)($method, 'https://api.spotify.com/v1' . $path, [
                'Authorization: Bearer ' . $this->tokens['access_token'], 'Content-Type: application/json',
            ], $body === null ? null : json_encode($body, JSON_THROW_ON_ERROR));
            if ($result['status'] === 401 && !$refreshed) { $this->refresh(); $refreshed = true; continue; }
            // A rejected 429 is safe to retry. Never replay an ambiguous POST/5xx.
            if ($result['status'] === 429) {
                $wait = max(1, (int) ($result['retry_after'] ?? 1));
                if ($wait > 30 || $attempt === 2) { throw new RuntimeException('Quota Spotify atteint. Attendre au moins ' . $wait . ' secondes avant de relancer.'); }
                sleep($wait); continue;
            }
            if ($result['status'] < 200 || $result['status'] >= 300) {
                throw new RuntimeException('Spotify HTTP ' . $result['status'] . '. Arrêt ; la prochaine exécution relira la playlist.');
            }
            return $result['data'];
        }
        throw new RuntimeException('Spotify ne répond pas après renouvellement.');
    }
}

function spotifyPages(SpotifyGateway $api, string $path): array
{
    $items = []; $seen = [];
    while ($path !== '') {
        if (isset($seen[$path]) || count($seen) >= 1000) { throw new RuntimeException('Pagination Spotify incohérente.'); }
        $seen[$path] = true;
        $page = $api->request('GET', $path);
        if (!isset($page['items']) || !is_array($page['items'])) { throw new RuntimeException('Réponse Spotify incomplète.'); }
        array_push($items, ...$page['items']);
        $path = $page['next'] ?? '';
    }
    return $items;
}

function trackKeys(array $track): array
{
    $keys = [];
    foreach ([$track['uri'] ?? '', $track['linked_from']['uri'] ?? ''] as $uri) {
        if ($uri !== '') { $keys[] = 'uri:' . $uri; }
    }
    if (!empty($track['external_ids']['isrc'])) { $keys[] = 'isrc:' . strtoupper($track['external_ids']['isrc']); }
    return $keys;
}

function planTracks(array $candidates, array $existing, array $config): array
{
    $seen = [];
    foreach ($existing as $entry) {
        $track = $entry['item'] ?? $entry['track'] ?? [];
        foreach (trackKeys($track) as $key) { $seen[$key] = true; }
    }
    usort($candidates, static fn($a, $b) => strcmp($b['release_date'], $a['release_date']) ?: strcmp($a['uri'], $b['uri']));
    $result = [];
    foreach ($candidates as $track) {
        if (!preg_match('/^spotify:track:[A-Za-z0-9]{22}$/', $track['uri'] ?? '') || ($track['is_local'] ?? false)) { continue; }
        if (($track['is_playable'] ?? true) === false || !empty($track['restrictions'])) { continue; }
        if ($track['release_date'] > gmdate('Y-m-d')) { continue; }
        if (!array_intersect(array_column($track['artists'] ?? [], 'id'), array_keys($config['artists']))) { continue; }
        if (in_array($track['id'], $config['excluded_track_ids'], true) || in_array($track['album_id'], $config['excluded_album_ids'], true)
            || (!empty($track['external_ids']['isrc']) && in_array(strtoupper($track['external_ids']['isrc']), array_map('strtoupper', $config['excluded_isrcs']), true))) { continue; }
        $keys = trackKeys($track);
        if (array_intersect($keys, array_keys($seen))) { continue; }
        foreach ($keys as $key) { $seen[$key] = true; }
        $result[] = $track;
    }
    return $result;
}

function discoverTracks(SpotifyGateway $api, array $config, array &$cache): array
{
    $albums = []; $tracks = [];
    $market = rawurlencode($config['market']);
    foreach (array_keys($config['artists']) as $id) {
        foreach (spotifyPages($api, '/artists/' . $id . '/albums?include_groups=album,single,appears_on,compilation&market=' . $market . '&limit=10') as $album) {
            if (!in_array($album['id'], $config['excluded_album_ids'], true)) { $albums[$album['id']] = $album; }
        }
    }
    foreach ($albums as $id => $album) {
        $entry = $cache[$market . ':' . $id] ?? [];
        if (($entry['at'] ?? 0) < time() - $config['cache_seconds']) {
            $albumTracks = [];
            foreach (spotifyPages($api, '/albums/' . $id . '/tracks?market=' . $market . '&limit=50') as $track) {
                if (!array_intersect(array_column($track['artists'] ?? [], 'id'), array_keys($config['artists']))) { continue; }
                // Full track metadata supplies ISRC for album/single deduplication.
                $albumTracks[] = $api->request('GET', '/tracks/' . $track['id'] . '?market=' . $market);
            }
            $entry = ['at' => time(), 'tracks' => $albumTracks];
            $cache[$market . ':' . $id] = $entry;
        }
        foreach ($entry['tracks'] as $track) {
            $track['release_date'] = $album['release_date'] ?? '0000';
            $track['album_id'] = $id;
            $tracks[] = $track;
        }
    }
    return $tracks;
}

function applyTracks(SpotifyGateway $api, string $playlistId, array $tracks, string $snapshot): int
{
    $added = 0;
    // Insert tail first so several batches still leave the newest songs at the top.
    foreach (array_reverse(array_chunk(array_column($tracks, 'uri'), 100)) as $batch) {
        $playlist = $api->request('GET', '/playlists/' . $playlistId);
        if (($playlist['snapshot_id'] ?? '') !== $snapshot) {
            throw new RuntimeException('Playlist modifiée pendant la synchronisation. Relancer pour recalculer les ajouts.');
        }
        $response = $api->request('POST', '/playlists/' . $playlistId . '/items', ['uris' => $batch, 'position' => 0]);
        if (empty($response['snapshot_id'])) { throw new RuntimeException('Ajout non confirmé. Relancer pour relire la playlist.'); }
        $snapshot = $response['snapshot_id'];
        $added += count($batch);
    }
    return $added;
}
