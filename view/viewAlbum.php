<?php
function renderCataloguePagination(array $pagination, string $location): void
{
    if ($pagination['pages'] <= 1) {
        return;
    }
    ?>
    <nav class="catalogPagination" aria-label="Pages du catalogue (<?= htmlspecialchars($location) ?>)">
        <?php if ($pagination['page'] > 1): ?>
            <a href="?page=<?= $pagination['page'] - 1 ?>#catalog" rel="prev">← Précédente</a>
        <?php endif; ?>
        <span aria-current="page">Page <?= $pagination['page'] ?> sur <?= $pagination['pages'] ?></span>
        <?php if ($pagination['page'] < $pagination['pages']): ?>
            <a href="?page=<?= $pagination['page'] + 1 ?>#catalog" rel="next">Suivante →</a>
        <?php endif; ?>
    </nav>
    <?php
}

function renderAlbum()
{
    $releases = require __DIR__ . '/../model/releases.php';
    $pagination = dfrCataloguePage($releases['catalog'], $_GET['page'] ?? 1);
    $releases['catalog'] = $pagination['items'];
    ob_start();
    ?>
    <main class="mainAlbum">
        <section class="albumHero">
            <h1>Nos sorties</h1>
            <p>Découvrez les sorties du label et notre catalogue musical</p>
            <p class="catalogCount"><?= $releases['total'] ?> sorties à découvrir</p>
            <?php if ($pagination['page'] === 1 && $releases['featured']): ?>
                <p class="catalogLink"><a href="#catalog">Aller au catalogue</a></p>
            <?php endif; ?>
        </section>

        <?php foreach (['featured' => 'À la une', 'catalog' => 'Notre catalogue'] as $section => $heading): ?>
            <?php if ($section === 'featured' && $pagination['page'] > 1) { continue; } ?>
            <section class="albumCatalog" id="<?= $section ?>">
                <h2><?= $heading ?></h2>
                <?php if ($section === 'catalog') { renderCataloguePagination($pagination, 'haut'); } ?>
                <div class="albumGrid">
                    <?php foreach ($releases[$section] as $release): ?>
                        <article class="albumCard">
                            <a class="albumImage coverLink" href="<?= htmlspecialchars($release['spotify']) ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?= htmlspecialchars($release['image']) ?>"
                                    alt="<?= htmlspecialchars($release['title'] . ' - ' . $release['artist']) ?>"
                                    loading="lazy" decoding="async">
                            </a>
                            <div class="albumInfo">
                                <h3><?= htmlspecialchars($release['title']) ?></h3>
                                <p class="artistName"><?= htmlspecialchars($release['artist']) ?></p>
                                <p class="releaseDate">
                                    <time datetime="<?= htmlspecialchars($release['date']) ?>"><?= dfrReleaseDateLabel($release['date']) ?></time>
                                </p>
                                    <a href="<?= htmlspecialchars($release['spotify']) ?>" class="btnListen"
                                        target="_blank" rel="noopener noreferrer"
                                        aria-label="<?= htmlspecialchars($release['link_label'] . ' — ' . $release['title'] . ' par ' . $release['artist']) ?>">
                                        <i class="fab fa-spotify" aria-hidden="true"></i>
                                        <?= htmlspecialchars($release['link_label']) ?>
                                    </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php if ($section === 'catalog') { renderCataloguePagination($pagination, 'bas'); } ?>
            </section>
        <?php endforeach; ?>
    </main>
    <?php
    return ob_get_clean();
}
