<?php
require_once __DIR__ . '/../model/site.php';
require_once __DIR__ . '/../model/seo.php';

function renderHeader(
    string $title = 'Dao Family Records',
    bool $includeArtistScript = false,
    array $metadata = [],
    array $stylesheets = []
): string
{
    $site = dfrSite();
    $navigation = $site['navigation'];
    $booking = $site['booking'];
    $desktopNavigation = [...$navigation, $booking];
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/index.php', PHP_URL_PATH);
    if ($currentPath === '/') { $currentPath = '/index.php'; }
    $description = $metadata['description'] ?? 'Dao Family Records, label de musique électronique à Toulouse. Découvrez nos artistes, sorties, clips et événements.';
    $canonical = $metadata['canonical'] ?? null;
    if ($canonical === null && $_GET === [] && in_array($currentPath, array_column($desktopNavigation, 0), true)) {
        $canonical = dfrAbsoluteUrl($currentPath === '/index.php' ? '/' : $currentPath);
    }
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <link rel="icon" type="image/png" href="/img/1logo_simple_dfr_blanc.png">
        <?php foreach (['style.css', 'icons.css', ...$stylesheets] as $stylesheet): ?>
            <link rel="stylesheet" href="/css/<?= $escape($stylesheet) ?>?v=<?= filemtime(__DIR__ . '/../css/' . $stylesheet) ?>">
        <?php endforeach; ?>
        <script src="/js/script.js?v=<?= filemtime(__DIR__ . '/../js/script.js') ?>" defer></script>
        <?php if ($includeArtistScript): ?>
            <script src="/js/scriptArtist.js?v=<?= filemtime(__DIR__ . '/../js/scriptArtist.js') ?>" defer></script>
        <?php endif; ?>
        <meta name="description" content="<?= $escape($description) ?>">
        <title><?= $escape($title) ?></title>
        <?php if ($canonical !== null): ?>
            <link rel="canonical" href="<?= $escape($canonical) ?>">
        <?php endif; ?>
        <?php if ($canonical !== null && isset($metadata['image'], $metadata['image_alt'])): ?>
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="Dao Family Records">
            <meta property="og:locale" content="fr_FR">
            <meta property="og:url" content="<?= $escape($canonical) ?>">
            <meta property="og:title" content="<?= $escape($title) ?>">
            <meta property="og:description" content="<?= $escape($description) ?>">
            <meta property="og:image" content="<?= $escape($metadata['image']) ?>">
            <meta property="og:image:alt" content="<?= $escape($metadata['image_alt']) ?>">
            <meta name="twitter:card" content="summary">
        <?php endif; ?>
    </head>

    <body>
        <header class="siteHeader">
            <nav class="navMax1000" aria-label="Navigation principale">
                <div class="navTop">
                    <a class="mobileBrand" href="/index.php">
                        <img src="/img/1logo_simple_dfr_blanc.png" alt="">
                        <span>Dao Family Records</span>
                    </a>
                    <a class="mobileBooking" href="<?= $booking[0] ?>"<?= $currentPath === $booking[0] ? ' aria-current="page"' : '' ?>><?= $booking[1] ?></a>
                </div>
                <div class="menuNav">
                    <ul>
                        <?php foreach ($navigation as [$path, $label, $icon]): ?>
                            <li><a href="<?= $path ?>"<?= $currentPath === $path ? ' aria-current="page"' : '' ?>>
                                <i class="fas <?= $icon ?>" aria-hidden="true"></i><span><?= $label ?></span>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>
            <nav class="navMin1000" aria-label="Navigation principale">
                <div class="navbarContainer">
                    <div class="navbarLogo">
                        <a href="/index.php">
                            <img class="logoNavMin" src="/img/1logo_simple_dfr_blanc.png" alt="Logo DFR">
                            <span>Dao Family Records</span>
                        </a>
                    </div>
                    <div class="navbarMenu">
                        <ul>
                            <?php foreach ($desktopNavigation as [$path, $label, $icon]): ?>
                                <li><a href="<?= $path ?>" class="navLink"<?= $currentPath === $path ? ' aria-current="page"' : '' ?>>
                                    <i class="fas <?= $icon ?>" aria-hidden="true"></i> <?= $label ?>
                                </a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="navbarSocial">
                        <?php foreach (['YouTube', 'Spotify', 'Instagram'] as $platform): ?>
                            <?php $social = $site['social'][$platform]; ?>
                            <a href="<?= htmlspecialchars($social['url']) ?>" class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($social['label']) ?>"><i
                                    class="fab <?= htmlspecialchars($social['icon']) ?>" aria-hidden="true"></i></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </nav>
        </header>
        <?php
        return ob_get_clean();
}
?>
