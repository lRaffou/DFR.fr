<?php
require_once __DIR__ . '/../components/releases.php';

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
    $spotifyMobileCovers = require __DIR__ . '/../model/album-mobile-covers.php';
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
                        <?= renderReleaseCard($release, true, $spotifyMobileCovers) ?>
                    <?php endforeach; ?>
                </div>
                <?php if ($section === 'catalog') { renderCataloguePagination($pagination, 'bas'); } ?>
            </section>
        <?php endforeach; ?>
    </main>
    <?php
    return ob_get_clean();
}
