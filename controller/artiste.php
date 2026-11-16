<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewArtiste.php';
require __DIR__ . '/../view/viewFooter.php';

$artists = require __DIR__ . '/../model/artists.php';
$requestedSlug = $_GET['artiste'] ?? null;
$profileId = null;
$title = 'Artistes | Dao Family Records';
$metadata = ['canonical' => dfrAbsoluteUrl('/controller/artiste.php')];

foreach ($artists as $id => $artist) {
    if (($artist['slug'] ?? $id) !== $requestedSlug) {
        continue;
    }
    $profileId = $id;
    $title = $artist['name'] . ' | Dao Family Records';
    $metadata = [
        'canonical' => dfrAbsoluteUrl(dfrArtistPath($id, $artist)),
        'description' => $artist['name'] . ' · ' . implode(' / ', $artist['genres'])
            . ' chez Dao Family Records. Biographie, musique et liens officiels.',
        'image' => dfrAbsoluteUrl($artist['card_image']['desktop']['src'] ?? $artist['image']),
        'image_alt' => 'Portrait de ' . $artist['name'],
    ];
    break;
}

echo renderHeader($title, true, $metadata, $profileId === null ? [] : ['artist-profile.css']);
echo renderArtiste($artists, $profileId);
echo renderFooter();
