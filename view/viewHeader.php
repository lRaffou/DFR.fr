<?php
function renderHeader($title = 'Dao Family Records', bool $includeArtistScript = false)
{
    $navigation = [
        ['/index.php', 'Accueil', 'fa-home'],
        ['/controller/evenement.php', 'Événements', 'fa-calendar-alt'],
        ['/controller/artiste.php', 'Artistes', 'fa-music'],
        ['/controller/video.php', 'Vidéos', 'fa-video'],
        ['/controller/album.php', 'Sorties', 'fa-compact-disc'],
    ];
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
                        <a href="https://www.youtube.com/@DaoFamilyRecords" class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="Dao Family Records sur YouTube"><i
                                class="fab fa-youtube"></i></a>
                        <a href="https://open.spotify.com/playlist/4z6XsMTJ8rsbSGyi20bz9T"
                            class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="Playlist Dao Family Records sur Spotify"><i class="fab fa-spotify"></i></a>
                        <a href="https://www.instagram.com/dao_family_records" class="socialLink" target="_blank" rel="noopener noreferrer" aria-label="Dao Family Records sur Instagram"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </nav>
        </header>
        <?php
        return ob_get_clean();
}
?>
