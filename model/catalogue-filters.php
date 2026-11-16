<?php
declare(strict_types=1);

function dfrCatalogueArtists(array $profiles): array
{
    $artists = [];
    foreach ($profiles as $id => $profile) {
        $slug = $profile['slug'] ?? $id;
        $spotifyUrl = $profile['social_spotify'] ?? '';
        $artists[$slug] = [
            'name' => $profile['name'],
            'spotify_id' => preg_match('~^https://open\.spotify\.com/artist/([A-Za-z0-9]{22})$~D', $spotifyUrl, $match) === 1
                ? $match[1]
                : null,
        ];
    }
    return $artists;
}

function dfrCatalogueMatchesArtist(array $release, array $artist): bool
{
    if ($artist['spotify_id'] !== null && in_array($artist['spotify_id'], $release['artist_ids'], true)) {
        return true;
    }
    $name = preg_quote($artist['name'], '~');
    foreach (explode(',', $release['artist']) as $credit) {
        if (preg_match('~\A' . $name . '\z~iu', trim($credit)) === 1) { return true; }
    }
    return false;
}

function dfrCatalogueIsCollaboration(array $release, array $frenzysax): bool
{
    // Keep the same interpretation as the collaboration slot in À la une.
    return count(array_unique($release['artist_ids'])) > 1
        || str_contains($release['artist'], ',')
        || dfrCatalogueMatchesArtist($release, $frenzysax);
}
