<?php
declare(strict_types=1);

function dfrReleaseDateValid(string $date): bool
{
    if (!preg_match('/^(\d{4})(?:-(\d{2})(?:-(\d{2}))?)?$/D', $date, $parts)) { return false; }
    return (int) $parts[1] >= 1900 && checkdate((int) ($parts[2] ?? 1), (int) ($parts[3] ?? 1), (int) $parts[1]);
}

function dfrReleaseDateLabel(string $date): string
{
    if (!dfrReleaseDateValid($date)) { return ''; }
    return implode('/', array_reverse(explode('-', $date)));
}

function dfrCleanRelease(array $release): ?array
{
    $url = $release['spotify'] ?? '';
    if (!is_string($url) || !preg_match('~^https://open\.spotify\.com/album/([A-Za-z0-9]{22})$~D', $url, $match)) { return null; }
    foreach (['title', 'artist', 'date'] as $key) {
        if (!isset($release[$key]) || !is_string($release[$key]) || trim($release[$key]) === '') { return null; }
    }
    if (!dfrReleaseDateValid($release['date']) || $release['date'] > gmdate('Y-m-d') || !is_array($release['artist_ids'] ?? [])) { return null; }
    $image = $release['image'] ?? '';
    if (!is_string($image) || (!preg_match('~^/img/[A-Za-z0-9_./-]+$~D', $image)
        && !preg_match('~^https://i\.scdn\.co/image/[A-Za-z0-9]+$~D', $image)) || str_contains($image, '..')) {
        $image = '/img/1logo_simple_dfr_blanc.png';
    }
    $artistIds = array_values(array_filter($release['artist_ids'] ?? [], static fn($id) => is_string($id) && preg_match('/^[A-Za-z0-9]{22}$/D', $id)));
    // Only these public fields may reach the website; never serialize a whole API response.
    return [
        'id' => $match[1], 'title' => $release['title'], 'artist' => $release['artist'], 'artist_ids' => $artistIds,
        'image' => $image, 'date' => $release['date'], 'spotify' => $url,
        'link_label' => ($release['link_label'] ?? '') === 'Écouter sur Spotify' ? 'Écouter sur Spotify' : 'Voir sur Spotify',
    ];
}

function dfrReadCatalogue(string $path): ?array
{
    if (!is_file($path) || !is_readable($path) || filesize($path) > 5 * 1024 * 1024) { return null; }
    try {
        $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data) || ($data['schema_version'] ?? null) !== 1 || !is_array($data['releases'] ?? null)
            || !is_array($data['excluded_album_ids'] ?? [])) { return null; }
        $clean = [];
        foreach ($data['releases'] as $release) {
            if (!is_array($release)) { return null; }
            $item = dfrCleanRelease($release);
            if ($item === null) { return null; }
            $clean[] = $item;
        }
        return ['releases' => $clean, 'excluded_album_ids' => $data['excluded_album_ids'] ?? [], 'generated_at' => $data['generated_at'] ?? null];
    } catch (JsonException $error) { return null; }
}

function dfrMergeReleases(array $seed, ?array $automatic): array
{
    $indexed = [];
    foreach (array_merge($seed['featured'], $seed['catalog'], $automatic['releases'] ?? []) as $release) {
        $clean = dfrCleanRelease($release);
        if ($clean === null || in_array($clean['id'], $automatic['excluded_album_ids'] ?? [], true)) { continue; }
        if (isset($indexed[$clean['id']])) {
            // Keep the editorial credits (including confirmed features) of existing cards.
            $clean['artist'] = $indexed[$clean['id']]['artist'];
            if (str_starts_with($indexed[$clean['id']]['image'], '/img/')) {
                $clean['image'] = $indexed[$clean['id']]['image'];
            }
        }
        $indexed[$clean['id']] = $clean;
    }
    $all = array_values($indexed);
    usort($all, static fn($a, $b) => strcmp($b['date'], $a['date']) ?: strcmp($a['id'], $b['id']));
    return ['featured' => array_slice($all, 0, 3), 'catalog' => array_slice($all, 3), 'total' => count($all)];
}

function dfrCataloguePage(array $items, mixed $requestedPage, int $size = 24): array
{
    $pages = max(1, (int) ceil(count($items) / $size));
    $number = is_scalar($requestedPage) ? filter_var($requestedPage, FILTER_VALIDATE_INT) : false;
    $current = min($pages, max(1, $number === false ? 1 : (int) $number));
    return ['items' => array_slice($items, ($current - 1) * $size, $size), 'page' => $current, 'pages' => $pages];
}
