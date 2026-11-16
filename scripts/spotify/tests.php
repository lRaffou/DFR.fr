<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/lib.php';
require __DIR__ . '/catalogue.php';

function check(bool $condition, string $message): void
{
    if (!$condition) { throw new RuntimeException($message); }
    echo "OK — $message\n";
}
function fails(callable $action, string $message): void
{
    $failed = false;
    try { $action(); } catch (RuntimeException $e) { $failed = true; }
    check($failed, $message);
}
function song(int $number, string $date = '2025-01-01', string $artist = 'artist-a', ?string $isrc = null): array
{
    $id = str_pad((string) $number, 22, '0', STR_PAD_LEFT);
    return ['id' => $id, 'uri' => 'spotify:track:' . $id, 'name' => 'Titre ' . $number,
        'artists' => [['id' => $artist]], 'release_date' => $date, 'album_id' => 'album-a',
        'is_playable' => true, 'external_ids' => $isrc ? ['isrc' => $isrc] : []];
}
final class FakeSpotify implements SpotifyGateway
{
    public array $calls = [];
    public function __construct(private $handler) {}
    public function request(string $method, string $path, ?array $body = null): array
    {
        $this->calls[] = [$method, $path, $body];
        return ($this->handler)($method, $path, $body);
    }
}
$config = ['artists' => ['artist-a' => 'A', 'artist-b' => 'B'], 'market' => 'FR',
    'excluded_track_ids' => [], 'excluded_album_ids' => [], 'excluded_isrcs' => [], 'cache_seconds' => 86400];
$one = song(1, '2025-01-01', 'artist-a', 'FR123');
$duplicate = song(2, '2025-03-01', 'artist-a', 'FR123');
$new = song(3, '2025-05-01', 'artist-b', 'FR456');
$unavailable = song(4); $unavailable['is_playable'] = false;
$restricted = song(5); $restricted['restrictions'] = ['reason' => 'unknown'];
$feat = song(6); $feat['artists'] = [['id' => 'outside'], ['id' => 'artist-b']];
$candidates = [$one, $duplicate, $new, $unavailable, $restricted, $feat, song(7, '2999-01-01'), song(8, '2025-01-01', 'outside')];
$plan = planTracks($candidates, [['item' => $one], ['item' => null], ['track' => song(99)]], $config);
check(array_column($plan, 'id') === [$new['id'], $feat['id']], 'Doublons URI/ISRC, participations, indisponibles et dates futures filtrés');
$plan = planTracks([$one, $duplicate, $new, $new], [], $config);
check(array_column($plan, 'id') === [$new['id'], $duplicate['id']], 'La version la plus récente est retenue, sans doublon entre artistes');
$excluded = $config; $excluded['excluded_track_ids'] = [$new['id']]; $excluded['excluded_isrcs'] = ['fr123'];
check(planTracks([$one, $duplicate, $new], [], $excluded) === [], 'Exclusions de titres et ISRC');
$excluded = $config; $excluded['excluded_album_ids'] = ['album-a'];
check(planTracks([$one], [], $excluded) === [], 'Exclusion des albums');
$legacy = song(10); $legacy['linked_from'] = ['uri' => $one['uri']];
check(planTracks([$legacy], [['track' => $one]], $config) === [], 'Anciennes réponses track et titres relinkés compatibles');

$paged = new FakeSpotify(static fn($m, $p) => $p === '/page1' ? ['items' => [1], 'next' => '/page2'] : ['items' => [2], 'next' => null]);
check(spotifyPages($paged, '/page1') === [1, 2], 'Toutes les pages sont parcourues');
fails(fn() => spotifyPages(new FakeSpotify(static fn() => ['items' => [], 'next' => '/loop']), '/loop'), 'Boucle de pagination refusée');
fails(fn() => spotifyPages(new FakeSpotify(static fn() => []), '/bad'), 'Réponse incomplète refusée');

$catalogue = new FakeSpotify(static function ($m, $p) use ($one, $new) {
    if (str_starts_with($p, '/artists/')) { return ['items' => [['id' => 'album-a', 'release_date' => '2025-03-01']], 'next' => null]; }
    if (str_starts_with($p, '/albums/')) { return ['items' => [$one, $new, song(8, '2025-01-01', 'outside')], 'next' => null]; }
    if (str_starts_with($p, '/tracks/' . $one['id'])) { return $one; }
    if (str_starts_with($p, '/tracks/' . $new['id'])) { return $new; }
    throw new RuntimeException('Requête inattendue');
});
$cache = [];
check(count(discoverTracks($catalogue, $config, $cache)) === 2 && count($catalogue->calls) === 5, 'Album partagé lu une fois ; autres artistes de compilation ignorés');
$before = count($catalogue->calls); discoverTracks($catalogue, $config, $cache);
check(count($catalogue->calls) - $before === 2, 'Cache réutilisé et discographies revérifiées');

$snapshot = 's0'; $playlist = ['manual-track']; $writes = 0;
$writer = new FakeSpotify(static function ($method, $path, $body) use (&$snapshot, &$playlist, &$writes) {
    if ($method === 'GET') { return ['snapshot_id' => $snapshot]; }
    check(count($body['uris']) <= 100 && $body['position'] === 0, 'Lot limité à 100 et inséré en tête');
    $playlist = array_merge($body['uris'], $playlist); $writes++; $snapshot = 's' . $writes;
    return ['snapshot_id' => $snapshot];
});
$many = array_map(static fn($n) => song($n), range(1, 205));
check(applyTracks($writer, 'playlist', $many, 's0') === 205 && $playlist === array_merge(array_column($many, 'uri'), ['manual-track']), '205 titres ordonnés, titres existants conservés');
fails(fn() => applyTracks(new FakeSpotify(static fn() => ['snapshot_id' => 'changed']), 'playlist', [$one], 'original'), 'Modification concurrente détectée avant écriture');
$empty = new FakeSpotify(static fn() => []);
check(applyTracks($empty, 'playlist', [], 's0') === 0 && !$empty->calls, 'Aucune écriture quand la playlist est à jour');

$temp = sys_get_temp_dir() . '/dfr-spotify-test-' . bin2hex(random_bytes(6));
$store = new PrivateStore($temp);
try {
    $store->write('credentials', ['client_id' => 'test-client', 'refresh_token' => 'test-refresh', 'access_token' => 'old', 'expires_at' => 0]);
    $refreshes = 0; $posts = 0;
    $transport = static function ($method, $url, $headers, $body) use (&$refreshes, &$posts) {
        if (str_contains($url, '/api/token')) { $refreshes++; return ['status' => 200, 'data' => ['access_token' => 'new', 'expires_in' => 3600]]; }
        if ($method === 'POST') { $posts++; return ['status' => 503, 'data' => []]; }
        return ['status' => 200, 'data' => ['id' => 'user']];
    };
    $client = new SpotifyClient($store, $transport);
    check($client->request('GET', '/me')['id'] === 'user' && $refreshes === 1 && $store->read('credentials')['refresh_token'] === 'test-refresh', 'Renouvellement conserve le refresh token non remplacé');
    fails(fn() => $client->request('POST', '/playlists/test/items', ['uris' => []]), 'Écriture ambiguë arrêtée');
    check($posts === 1, 'Un POST incertain n’est jamais rejoué');
    $store->write('credentials', ['client_id' => 'test-client', 'refresh_token' => 'expired', 'expires_at' => 0]);
    $expired = new SpotifyClient($store, static fn() => ['status' => 400, 'data' => ['error' => 'invalid_grant']]);
    fails(fn() => $expired->request('GET', '/me'), 'Une autorisation expirée provoque un arrêt');
    check(!isset($store->read('credentials')['refresh_token']), 'Le jeton expiré est supprimé sans nouvelle tentative');
    fails(fn() => $client->request('GET', 'https://evil.example/me'), 'Jeton jamais envoyé à un hôte externe');
    fails(fn() => new PrivateStore(__DIR__), 'Dossier privé dans le site refusé');
    $lock = $store->lock(); fails(fn() => $store->lock(), 'Verrou empêche les exécutions simultanées'); fclose($lock);
    $store->write('credentials', ['client_id' => 'test-client', 'refresh_token' => 'test-refresh', 'access_token' => 'test', 'expires_at' => time() + 3600]);
    $requests = 0;
    $limited = new SpotifyClient($store, static function () use (&$requests) {
        $requests++; return ['status' => 429, 'data' => [], 'retry_after' => 77502];
    });
    fails(fn() => $limited->request('GET', '/me'), 'Un quota long arrête immédiatement la synchronisation');
    check($requests === 1 && $store->read('rate-limit')['retry_at'] >= time() + 77500, 'Le délai Spotify est conservé entre les exécutions');
    $store->write('credentials', ['client_id' => 'test-client', 'refresh_token' => 'test-refresh', 'access_token' => 'test', 'expires_at' => 0]);
    $resumed = new SpotifyClient($store, static function () use (&$requests) { $requests++; return ['status' => 200, 'data' => ['id' => 'user']]; });
    fails(fn() => $resumed->request('GET', '/me'), 'La prochaine exécution respecte la pause avant de renouveler le jeton');
    check($requests === 1, 'Aucune requête réseau pendant la pause');
    $store->write('rate-limit', ['retry_at' => time() - 1]);
    $store->write('credentials', ['client_id' => 'test-client', 'refresh_token' => 'test-refresh', 'access_token' => 'test', 'expires_at' => time() + 3600]);
    $ready = new SpotifyClient($store, static fn() => ['status' => 200, 'data' => ['id' => 'user']]);
    check($ready->request('GET', '/me')['id'] === 'user', 'Les requêtes reprennent après le délai imposé');
} finally {
    foreach (['credentials.json', 'sync.lock', 'rate-limit.json'] as $name) { if (is_file($temp . '/' . $name)) { unlink($temp . '/' . $name); } }
    rmdir($temp);
}
$artistId = str_repeat('a', 22);
$albumId = str_repeat('b', 22);
$otherAlbumId = str_repeat('c', 22);
$catalogConfig = $config;
$catalogConfig['artists'] = [$artistId => 'Artiste DFR'];
$release = ['id' => $albumId, 'name' => 'Nouvelle sortie', 'release_date' => '2025-06',
    'artists' => [['id' => $artistId, 'name' => 'Artiste DFR']],
    'images' => [
        ['url' => 'https://i.scdn.co/image/cover640', 'width' => 640, 'height' => 640],
        ['url' => 'https://i.scdn.co/image/cover300', 'width' => 300, 'height' => 300],
        ['url' => 'https://i.scdn.co/image/cover64', 'width' => 64, 'height' => 64],
    ], 'secret' => 'NEVER-PUBLISH'];
$track = song(22, '2025-06', $artistId); $track['album_id'] = $albumId;
$built = buildReleaseCatalogue([$release, $release], [$track], $catalogConfig, null);
check(count($built['releases']) === 1 && !str_contains(json_encode($built), 'NEVER-PUBLISH'), 'Catalogue sans doublon et uniquement avec les métadonnées publiques');
check($built['releases'][0]['image'] === $release['images'][0]['url']
    && $built['releases'][0]['mobile_image'] === $release['images'][1]['url'],
    'La miniature Spotify de 300 px est publiée avec la pochette d’origine');
$incompleteArt = $release;
$incompleteArt['images'] = [$release['images'][0], $release['images'][2],
    ['url' => 'https://example.com/image/externe', 'width' => 300, 'height' => 300],
    ['url' => 'https://i.scdn.co/image/crop300', 'width' => 300, 'height' => 200]];
check(!isset(buildReleaseCatalogue([$incompleteArt], [$track], $catalogConfig, null)['releases'][0]['mobile_image']),
    'Une miniature trop petite, recadrée ou externe est ignorée');
check((buildReleaseCatalogue([$incompleteArt], [$track], $catalogConfig, $built)['releases'][0]['mobile_image'] ?? null)
    === $release['images'][1]['url'], 'La miniature existante est conservée si la même pochette revient sans taille mobile');
$incompleteArt['images'][0]['url'] = 'https://i.scdn.co/image/replacement640';
check(!isset(buildReleaseCatalogue([$incompleteArt], [$track], $catalogConfig, $built)['releases'][0]['mobile_image']),
    'Le changement de pochette écarte son ancienne miniature');
check(dfrReleaseDateLabel('2025') === '2025' && dfrReleaseDateLabel('2025-06') === '06/2025'
    && dfrReleaseDateLabel('2025-06-03') === '03/06/2025' && !dfrReleaseDateValid('2025-02-30'), 'Précision des dates conservée sans inventer de jour');
$unavailableTrack = $track; $unavailableTrack['is_playable'] = false;
check(buildReleaseCatalogue([$release], [$unavailableTrack], $catalogConfig, null)['releases'] === [], 'Pas de nouvelle fiche pour une sortie indisponible');
$futureRelease = $release; $futureRelease['release_date'] = '2999-01-01';
check(buildReleaseCatalogue([$futureRelease], [$track], $catalogConfig, null)['releases'] === [], 'Les sorties futures ne sont pas publiées');
$compilation = $release; $compilation['artists'] = [['id' => 'outside', 'name' => 'Artiste sans rapport']];
$track['artists'][0]['name'] = 'Artiste DFR';
check(buildReleaseCatalogue([$compilation], [$track], $catalogConfig, null)['releases'][0]['artist'] === 'Artiste DFR', 'Les compilations créditent les participants concernés');
$retained = buildReleaseCatalogue([], [], $catalogConfig, $built);
check(count($retained['releases']) === 1 && $retained['releases'][0]['link_label'] === 'Voir sur Spotify', 'Les sorties historiques restent présentes après leur disparition de Spotify');
$catalogConfig['excluded_album_ids'] = [$albumId];
check(buildReleaseCatalogue([$release], [$track], $catalogConfig, $built)['releases'] === [], 'Une exclusion retire aussi une fiche automatique existante');
$seed = require __DIR__ . '/../../model/releases.seed.php';
check(dfrMergeReleases($seed, null)['total'] === 13, 'Les 13 fiches initiales restent disponibles sans cache');
check(array_column(dfrMergeReleases($seed, null)['featured'], 'title') === ['Red Destruction', 'Versus', 'Discoteca'],
    'À la une réserve une place à Red Marshal, une collaboration et un autre artiste');
$newSolo = dfrCleanRelease($seed['featured'][0]);
$newSolo['spotify'] = 'https://open.spotify.com/album/' . str_repeat('d', 22);
$newSolo['date'] = '2026-10-01'; $newSolo['title'] = 'Nouveau solo';
$newCollab = dfrCleanRelease($seed['featured'][1]);
$newCollab['spotify'] = 'https://open.spotify.com/album/' . str_repeat('e', 22);
$newCollab['date'] = '2026-10-02'; $newCollab['title'] = 'Nouvelle collaboration';
$newCollab['artist'] = 'Red Marshal';
$newCollab['artist_ids'] = ['3zpewQ11GYG2ev9Ngdsvi9', '5PyJWzxbDt81teQi2ZzL8n'];
$newOther = dfrCleanRelease($seed['featured'][2]);
$newOther['spotify'] = 'https://open.spotify.com/album/' . str_repeat('f', 22);
$newOther['date'] = '2026-10-03'; $newOther['title'] = 'Nouveau titre Fernando';
$selection = dfrMergeReleases($seed, ['releases' => [$newSolo, $newCollab, $newOther]]);
check(array_column($selection['featured'], 'title') === ['Nouveau solo', 'Nouvelle collaboration', 'Nouveau titre Fernando']
    && $selection['catalog'][0]['title'] === 'Red Destruction'
    && count(array_unique(array_column(array_merge($selection['featured'], $selection['catalog']), 'id'))) === 16,
    'Chaque catégorie se renouvelle avec sa dernière sortie, sans perdre ni dupliquer les autres fiches');
check(count(dfrMergeReleases(['featured' => [], 'catalog' => []], ['releases' => [$newSolo]])['featured']) === 1,
    'Une catégorie absente ne crée pas de carte vide ou de doublon');
$frenzysax = $newOther;
$frenzysax['spotify'] = 'https://open.spotify.com/album/30u1Cqo1BArnSTrdpmdf6K';
$frenzysax['title'] = 'Live The Moment'; $frenzysax['date'] = '2026-11-06';
$frenzysax['artist'] = 'Frenzysax'; $frenzysax['artist_ids'] = ['4NqtEeAINDNe0G3FiHbqbs'];
$frenzysaxSelection = dfrMergeReleases($seed, ['releases' => [$frenzysax]]);
check(array_column($frenzysaxSelection['featured'], 'title') === ['Red Destruction', 'Live The Moment', 'Discoteca']
    && $frenzysaxSelection['featured'][1]['artist'] === 'Frenzysax'
    && $frenzysaxSelection['total'] === 14
    && in_array('Versus', array_column($frenzysaxSelection['catalog'], 'title'), true),
    'Frenzysax occupe la place collaboration sans modifier les crédits ni perdre les autres sorties');
$seedCard = $built['releases'][0]; $seedCard['image'] = '/img/albums/webp/Releases-1.webp'; $seedCard['artist'] = 'Artiste DFR, invitée confirmée';
$merged = dfrMergeReleases(['featured' => [$seedCard], 'catalog' => []], $built);
check($merged['total'] === 1 && $merged['featured'][0]['image'] === $seedCard['image']
    && $merged['featured'][0]['artist'] === $seedCard['artist']
    && !isset($merged['featured'][0]['mobile_image']), 'Fusion sans doublon ni perte de pochette ou de crédit éditorial');
$badThumbnail = $built['releases'][0];
$badThumbnail['mobile_image'] = 'https://i.scdn.co.example.org/image/externe';
check(!isset(dfrCleanRelease($badThumbnail)['mobile_image']), 'Un champ miniature mal formé ne bloque pas la fiche');
$malicious = $seedCard; $malicious['spotify'] = 'javascript:alert(1)';
check(dfrCleanRelease($malicious) === null, 'Les liens Spotify externes ou dangereux sont refusés');
check(dfrCataloguePage(range(1, 49), 999)['items'] === [49] && dfrCataloguePage(range(1, 49), ['bad'])['page'] === 1,
    'Pagination bornée et paramètres invalides tolérés');
$catalogDir = sys_get_temp_dir() . '/dfr-catalogue-test-' . bin2hex(random_bytes(6));
mkdir($catalogDir);
$catalogPath = $catalogDir . '/releases.json';
try {
    publishReleaseCatalogue($catalogPath, $built);
    $published = dfrReadCatalogue($catalogPath)['releases'][0];
    check($published['id'] === $albumId && $published['mobile_image'] === $release['images'][1]['url'], 'Publication et lecture du catalogue public avec miniature');
    $original = file_get_contents($catalogPath);
    fails(fn() => publishReleaseCatalogue($catalogPath, ['schema_version' => 1, 'releases' => [['bad' => true]]]), 'Une publication invalide est arrêtée');
    check(file_get_contents($catalogPath) === $original, 'La dernière version valide est conservée en cas d’échec');
    file_put_contents($catalogPath, '{invalid');
    check(dfrMergeReleases($seed, dfrReadCatalogue($catalogPath))['total'] === 13, 'Un cache corrompu rétablit le catalogue initial');
} finally { unlink($catalogPath); rmdir($catalogDir); }
echo "Tous les tests sont passés.\n";
