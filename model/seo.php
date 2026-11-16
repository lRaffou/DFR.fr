<?php
const DFR_SITE_ORIGIN = 'https://daofamilyrecords.fr';

function dfrAbsoluteUrl(string $path): string
{
    return str_starts_with($path, '/') ? DFR_SITE_ORIGIN . $path : $path;
}

function dfrArtistPath(string $id, array $artist): string
{
    return '/controller/artiste.php?artiste=' . rawurlencode($artist['slug'] ?? $id);
}
