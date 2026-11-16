<?php
require_once __DIR__ . '/model/site.php';
require_once __DIR__ . '/model/seo.php';

$site = dfrSite();
$paths = array_column([...$site['navigation'], $site['booking']], 0);
$artists = require __DIR__ . '/model/artists.php';
foreach ($artists as $id => $artist) {
    $paths[] = dfrArtistPath($id, $artist);
}

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($paths as $path): ?>
    <url><loc><?= htmlspecialchars(dfrAbsoluteUrl($path === '/index.php' ? '/' : $path), ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc></url>
<?php endforeach; ?>
</urlset>
