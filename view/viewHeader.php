<?php
require_once __DIR__ . '/../model/site.php';

function renderHeader(string $title = 'Dao Family Records', bool $includeArtistScript = false): string
{
    $site = dfrSite();
    $navigation = $site['navigation'];
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/index.php', PHP_URL_PATH);
    if ($currentPath === '/') { $currentPath = '/index.php'; }
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <link rel="icon" type="image/png" href="/img/1logo_simple_dfr_blanc.png">
        <link rel="stylesheet" href="/css/style.css?v=<?= filemtime(__DIR__ . '/../css/style.css') ?>">
        <link rel="stylesheet" href="/css/icons.css?v=<?= filemtime(__DIR__ . '/../css/icons.css') ?>">
        <script src="/js/script.js?v=<?= filemtime(__DIR__ . '/../js/script.js') ?>" defer></script>
        <?php if ($includeArtistScript): ?>
            <script src="/js/scriptArtist.js?v=<?= filemtime(__DIR__ . '/../js/scriptArtist.js') ?>" defer></script>
        <?php endif; ?>
        <meta name="description" content="Dao Family Records, label de musique électronique à Toulouse. Découvrez nos artistes, sorties, clips et événements.">
        <title><?= htmlspecialchars($title) ?></title>
    </head>

    <body>
        <header>
            <nav class="navMax1000" aria-label="Navigation principale">
                <div class="navTop">
                    <a class="mobileBrand" href="/index.php">
                        <img src="/img/1logo_simple_dfr_blanc.png" alt="">
                        <span>Dao Family Records</span>
                    </a>
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
                            <?php foreach ($navigation as [$path, $label, $icon]): ?>
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
