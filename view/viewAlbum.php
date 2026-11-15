<?php
function renderAlbum()
{
    $releases = require __DIR__ . '/../model/releases.php';
    ob_start();
    ?>
    <main class="mainAlbum">
        <section class="albumHero">
            <h1>Nos sorties</h1>
            <p>Découvrez les sorties du label et notre catalogue musical</p>
        </section>

        <?php foreach (['featured' => 'À la une', 'catalog' => 'Notre catalogue'] as $section => $heading): ?>
            <section class="albumCatalog">
                <h2><?= $heading ?></h2>
                <div class="albumGrid">
                    <?php foreach ($releases[$section] as $release): ?>
                        <article class="albumCard">
                            <div class="albumImage">
                                <img src="<?= htmlspecialchars($release['image']) ?>"
                                    alt="<?= htmlspecialchars($release['title'] . ' - ' . $release['artist']) ?>"
                                    loading="lazy">
                                <div class="albumOverlay">
                                    <a href="<?= htmlspecialchars($release['spotify']) ?>" class="btnListen"
                                        target="_blank" rel="noopener noreferrer"
                                        aria-label="<?= htmlspecialchars($release['link_label'] . ' — ' . $release['title'] . ' par ' . $release['artist']) ?>">
                                        <i class="fab fa-spotify" aria-hidden="true"></i>
                                        <?= htmlspecialchars($release['link_label']) ?>
                                    </a>
                                </div>
                            </div>
                            <div class="albumInfo">
                                <h3><?= htmlspecialchars($release['title']) ?></h3>
                                <p class="artistName"><?= htmlspecialchars($release['artist']) ?></p>
                                <p class="releaseDate">
                                    <time datetime="<?= $release['date'] ?>"><?= (new DateTimeImmutable($release['date']))->format('d/m/Y') ?></time>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
    <?php
    return ob_get_clean();
}
