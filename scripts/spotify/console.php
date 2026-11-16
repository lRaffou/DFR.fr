<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/lib.php';
require __DIR__ . '/catalogue.php';
umask(0077);

function authorizeSpotify(PrivateStore $store, string $clientId): void
{
    if (!preg_match('/^[a-f0-9]{32}$/i', $clientId)) { throw new RuntimeException('Client ID Spotify invalide.'); }
    $redirect = 'http://127.0.0.1:8765/callback';
    $base64url = static fn(string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    $verifier = $base64url(random_bytes(64));
    $state = bin2hex(random_bytes(32));
    $server = stream_socket_server('tcp://127.0.0.1:8765', $errno, $error);
    if (!$server) { throw new RuntimeException('Le port local 8765 est indisponible.'); }
    $url = 'https://accounts.spotify.com/authorize?' . http_build_query([
        'client_id' => $clientId, 'response_type' => 'code', 'redirect_uri' => $redirect,
        'scope' => 'playlist-modify-public', 'state' => $state,
        'code_challenge_method' => 'S256', 'code_challenge' => $base64url(hash('sha256', $verifier, true)),
    ]);
    echo "Ouvrir ce lien pour connecter le compte propriétaire de DFR (valable 10 minutes) :\n" . $url . "\n";
    $deadline = time() + 600;
    try {
        while (time() < $deadline) {
            $connection = @stream_socket_accept($server, 5);
            if (!$connection) { continue; }
            stream_set_timeout($connection, 3);
            $request = fgets($connection, 8192) ?: '';
            $parts = explode(' ', trim($request));
            $target = $parts[1] ?? '';
            parse_str(parse_url($target, PHP_URL_QUERY) ?? '', $query);
            $valid = ($parts[0] ?? '') === 'GET' && parse_url($target, PHP_URL_PATH) === '/callback'
                && is_string($query['state'] ?? null) && hash_equals($state, $query['state']);
            if (!$valid) {
                fwrite($connection, "HTTP/1.1 400 Bad Request\r\nConnection: close\r\n\r\nRequete invalide.");
                fclose($connection); continue;
            }
            try {
                if (!empty($query['error']) || !is_string($query['code'] ?? null)) { throw new RuntimeException('Autorisation Spotify annulée.'); }
                $result = spotifyHttp('POST', 'https://accounts.spotify.com/api/token', ['Content-Type: application/x-www-form-urlencoded'], http_build_query([
                    'grant_type' => 'authorization_code', 'client_id' => $clientId, 'code' => $query['code'],
                    'redirect_uri' => $redirect, 'code_verifier' => $verifier,
                ]));
                if ($result['status'] !== 200 || empty($result['data']['refresh_token']) || empty($result['data']['access_token'])) {
                    throw new RuntimeException('Connexion Spotify refusée (HTTP ' . $result['status'] . ').');
                }
                $profile = spotifyHttp('GET', 'https://api.spotify.com/v1/me', ['Authorization: Bearer ' . $result['data']['access_token']], null);
                $config = require __DIR__ . '/config.php';
                if ($profile['status'] !== 200 || ($profile['data']['id'] ?? '') !== $config['owner_id']) {
                    throw new RuntimeException('Le compte connecté ne correspond pas au propriétaire de la playlist DFR.');
                }
                $store->write('credentials', array_merge($result['data'], [
                    'client_id' => $clientId, 'authorized_at' => time(), 'expires_at' => time() + (int) $result['data']['expires_in'],
                ]));
                fwrite($connection, "HTTP/1.1 200 OK\r\nContent-Type: text/html; charset=utf-8\r\nCache-Control: no-store\r\nReferrer-Policy: no-referrer\r\nConnection: close\r\n\r\n<!doctype html><html lang=fr><meta charset=utf-8><title>DFR connecté</title><h1>Spotify est connecté à DFR</h1><p>Tu peux fermer cette page. La synchronisation peut maintenant être testée.</p></html>");
                echo "Compte DFR connecté. Jetons enregistrés dans le dossier privé.\n";
            } catch (Throwable $failure) {
                fwrite($connection, "HTTP/1.1 400 Bad Request\r\nCache-Control: no-store\r\nConnection: close\r\n\r\nConnexion impossible. Voir le terminal.");
                throw $failure;
            } finally { fclose($connection); }
            return;
        }
        throw new RuntimeException('Connexion expirée. Relancer authorize.');
    } finally { fclose($server); }
}

try {
    $command = $argv[1] ?? 'help';
    if (!in_array($command, ['authorize', 'sync'], true)) {
        echo "DFR Spotify\n  php console.php authorize --client-id=ID --state-dir=CHEMIN_PRIVE\n  php console.php sync --state-dir=CHEMIN_PRIVE [--catalogue-file=CHEMIN_PUBLIC/releases.json] [--apply]\nSans --apply, la playlist et le catalogue public ne sont pas modifiés.\n";
        exit($command === 'help' ? 0 : 1);
    }
    $options = [];
    foreach (array_slice($argv, 2) as $argument) {
        if ($argument === '--apply') { $options['apply'] = true; continue; }
        if (preg_match('/^--(state-dir|client-id|catalogue-file)=(.+)$/', $argument, $matches)) { $options[$matches[1]] = $matches[2]; continue; }
        throw new RuntimeException('Option inconnue. Utiliser help.');
    }
    $directory = $options['state-dir'] ?? getenv('DFR_SPOTIFY_STATE_DIR');
    if (!$directory) { throw new RuntimeException('--state-dir est obligatoire et doit désigner un dossier hors du site.'); }
    $store = new PrivateStore($directory);
    $lock = $store->lock();
    if ($command === 'authorize') { authorizeSpotify($store, $options['client-id'] ?? ''); exit(0); }
    if (isset($options['catalogue-file'])) { validateCatalogueTarget($options['catalogue-file']); }
    $config = require __DIR__ . '/config.php';
    $api = new SpotifyClient($store);
    $profile = $api->request('GET', '/me');
    if (($profile['id'] ?? '') !== $config['owner_id']) { throw new RuntimeException('Le compte Spotify connecté ne correspond pas à DFR.'); }
    $id = $config['playlist_id'];
    $playlist = $api->request('GET', '/playlists/' . $id);
    if (($playlist['owner']['id'] ?? '') !== $config['owner_id'] || ($playlist['public'] ?? null) !== true || empty($playlist['snapshot_id'])) {
        throw new RuntimeException('La cible doit être la playlist publique du propriétaire DFR.');
    }
    $existing = spotifyPages($api, '/playlists/' . $id . '/items?market=' . $config['market'] . '&limit=50');
    $cache = $store->read('catalogue-cache');
    $fingerprint = hash('sha256', json_encode([$config['artists'], $config['market'], $config['owner_id']]));
    if (($cache['fingerprint'] ?? '') !== $fingerprint) { $cache = ['fingerprint' => $fingerprint, 'albums' => []]; }
    $albums = [];
    try {
        $candidates = discoverTracks($api, $config, $cache['albums'], $albums);
    } finally {
        // Retain completed album reads if Spotify rate-limits a later request.
        $store->write('catalogue-cache', $cache);
    }
    $planned = planTracks($candidates, $existing, $config);
    $report = ['at' => gmdate(DATE_ATOM), 'mode' => isset($options['apply']) ? 'apply' : 'dry-run',
        'playlist_id' => $id, 'existing' => count($existing), 'candidates' => count($candidates),
        'planned' => array_map(static fn($t) => ['uri' => $t['uri'], 'name' => $t['name'], 'release_date' => $t['release_date']], $planned),
    ];
    echo count($existing) . ' titres présents ; ' . count($planned) . " ajouts prévus.\n";
    foreach ($planned as $track) { echo $track['release_date'] . ' — ' . $track['name'] . ' — ' . $track['uri'] . "\n"; }
    $catalogue = null;
    if (isset($options['catalogue-file'])) {
        $catalogue = buildReleaseCatalogue($albums, $candidates, $config, dfrReadCatalogue($options['catalogue-file']), $existing);
        $report['catalogue_releases'] = count($catalogue['releases']);
        echo $report['catalogue_releases'] . " sorties prévues au catalogue automatique.\n";
    }
    if (isset($options['apply'])) {
        // A playlist write failure should not discard a catalogue built from successful reads.
        if ($catalogue !== null) {
            publishReleaseCatalogue($options['catalogue-file'], $catalogue);
            echo "Catalogue publié.\n";
        }
        $report['added'] = applyTracks($api, $id, $planned, $playlist['snapshot_id']);
        if ($planned !== []) {
            // Check every requested URI after successful writes. An uncertain result is never replayed here.
            $after = spotifyPages($api, '/playlists/' . $id . '/items?market=' . $config['market'] . '&limit=50');
            $confirmed = [];
            foreach ($after as $entry) { foreach (trackKeys($entry['item'] ?? $entry['track'] ?? []) as $key) { $confirmed[$key] = true; } }
            foreach ($planned as $track) {
                if (!array_intersect(trackKeys($track), array_keys($confirmed))) { throw new RuntimeException('Ajout non visible après écriture. Relire la playlist avant de relancer.'); }
            }
        }
        $store->write('last-success', $report);
        echo $report['added'] . " titres ajoutés et vérifiés.\n";
    } else { $store->write('last-preview', $report); echo "Simulation terminée. Utiliser --apply pour ajouter les titres.\n"; }
} catch (Throwable $failure) {
    fwrite(STDERR, gmdate(DATE_ATOM) . ' ERREUR : ' . $failure->getMessage() . "\n");
    exit(1);
}
