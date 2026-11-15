<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/../../model/release-catalogue.php';

function spotifyMobileCover(array $images): ?string
{
    $full = $images[0] ?? [];
    $fullWidth = $full['width'] ?? null;
    $fullHeight = $full['height'] ?? null;
    if (!is_int($fullWidth) || !is_int($fullHeight) || $fullWidth < 1 || $fullHeight < 1) {
        return null;
    }

    // Les pochettes mobiles s’affichent jusqu’à 106 px. Spotify fournit généralement 300 px.
    $bestWidth = min(385, $fullWidth);
    $bestUrl = null;
    foreach ($images as $image) {
        $width = $image['width'] ?? null;
        $height = $image['height'] ?? null;
        $url = $image['url'] ?? null;
        if (is_int($width) && is_int($height) && $width >= 256 && $width < $bestWidth && $height > 0
            && abs($height - $width * ($fullHeight / $fullWidth)) <= 1 && dfrSpotifyImageValid($url)) {
            $bestWidth = $width;
            $bestUrl = $url;
        }
    }
    return $bestUrl;
}

function buildReleaseCatalogue(array $albums, array $tracks, array $config, ?array $previous): array
{
    $eligible = [];
    foreach ($tracks as $track) {
        if (eligibleTrack($track, $config)) { $eligible[$track['album_id']][] = $track; }
    }
    $indexed = [];
    foreach ($previous['releases'] ?? [] as $release) {
        $clean = dfrCleanRelease($release);
        if ($clean !== null && !in_array($clean['id'], $config['excluded_album_ids'], true)) {
            $clean['link_label'] = 'Voir sur Spotify';
            $indexed[$clean['id']] = $clean;
        }
    }
    foreach ($albums as $album) {
        $id = $album['id'] ?? '';
        if (empty($eligible[$id]) || !empty($album['restrictions']) || empty($album['name'])) { continue; }
        $artists = $album['artists'] ?? [];
        // Credit appearances to the performers, not an unrelated compilation's artist list.
        if (!array_intersect(array_column($artists, 'id'), array_keys($config['artists']))) {
            $artists = [];
        }
        $artists = array_column($artists, null, 'id');
        foreach ($eligible[$id] as $track) {
            foreach ($track['artists'] ?? [] as $artist) {
                if (!empty($artist['id']) && !empty($artist['name'])) { $artists[$artist['id']] = $artist; }
            }
        }
        $artists = array_values($artists);
        $names = array_values(array_unique(array_filter(array_column($artists, 'name'), 'is_string')));
        $images = is_array($album['images'] ?? null) ? $album['images'] : [];
        $clean = dfrCleanRelease([
            'title' => $album['name'], 'artist' => implode(', ', $names), 'artist_ids' => array_column($artists, 'id'),
            'date' => $album['release_date'] ?? '', 'image' => $images[0]['url'] ?? '',
            'mobile_image' => spotifyMobileCover($images),
            'spotify' => 'https://open.spotify.com/album/' . $id, 'link_label' => 'Écouter sur Spotify',
        ]);
        if ($clean === null) { continue; }
        $old = $indexed[$id] ?? null;
        if (!isset($clean['mobile_image']) && isset($old['mobile_image']) && $clean['image'] === $old['image']) {
            $clean['mobile_image'] = $old['mobile_image'];
        }
        $indexed[$id] = $clean;
    }
    $releases = array_values($indexed);
    usort($releases, static fn($a, $b) => strcmp($b['date'], $a['date']) ?: strcmp($a['id'], $b['id']));
    return ['schema_version' => 1, 'generated_at' => gmdate(DATE_ATOM), 'releases' => $releases,
        'excluded_album_ids' => array_values($config['excluded_album_ids'])];
}

function validateCatalogueTarget(string $path): void
{
    if (!preg_match('~^(?:[A-Za-z]:[\\\\/]|/)~', $path) || basename($path) !== 'releases.json'
        || !is_dir(dirname($path)) || !is_writable(dirname($path)) || is_link($path)) {
        throw new RuntimeException('Le catalogue doit cibler un fichier releases.json dans un dossier absolu existant et accessible.');
    }
}

function publishReleaseCatalogue(string $path, array $catalogue): void
{
    validateCatalogueTarget($path);
    $temp = tempnam(dirname($path), '.catalogue-');
    try {
        if (!$temp || file_put_contents($temp, json_encode($catalogue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)) === false
            || dfrReadCatalogue($temp) === null) { throw new RuntimeException('Catalogue invalide : la version précédente est conservée.'); }
        if (DIRECTORY_SEPARATOR === '/' && !chmod($temp, 0644)) { throw new RuntimeException('Permissions du catalogue impossibles.'); }
        if (!rename($temp, $path)) { throw new RuntimeException('Publication du catalogue impossible.'); }
    } finally { if ($temp && is_file($temp)) { unlink($temp); } }
}
