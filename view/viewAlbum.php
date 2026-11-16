<?php
require_once __DIR__ . '/../components/releases.php';
require_once __DIR__ . '/../model/catalogue-filters.php';

function renderCataloguePagination(array $pagination, string $location, array $filters): void
{
    if ($pagination['pages'] <= 1) {
        return;
    }
    $pageUrl = static fn(int $page): string => htmlspecialchars('?' . http_build_query($filters + ['page' => $page]) . '#catalog');
    ?>
    <nav class="catalogPagination" aria-label="Pages du catalogue (<?= htmlspecialchars($location) ?>)">
        <?php if ($pagination['page'] > 1): ?>
            <a href="<?= $pageUrl($pagination['page'] - 1) ?>" rel="prev">← Précédente</a>
        <?php endif; ?>
        <span aria-current="page">Page <?= $pagination['page'] ?> sur <?= $pagination['pages'] ?></span>
        <?php if ($pagination['page'] < $pagination['pages']): ?>
            <a href="<?= $pageUrl($pagination['page'] + 1) ?>" rel="next">Suivante →</a>
        <?php endif; ?>
    </nav>
    <?php
}

function renderAlbum()
{
    $releases = require __DIR__ . '/../model/releases.php';
    $spotifyMobileCovers = require __DIR__ . '/../model/album-mobile-covers.php';
    $artists = dfrCatalogueArtists(require __DIR__ . '/../model/artists.php');
    $artist = $_GET['artiste'] ?? '';
    $artist = is_string($artist) && isset($artists[$artist]) ? $artist : '';
    $collaborations = ($_GET['collaborations'] ?? null) === '1';
    $filters = [];
    if ($artist !== '') { $filters['artiste'] = $artist; }
    if ($collaborations) { $filters['collaborations'] = '1'; }

    $catalogue = array_merge($releases['featured'], $releases['catalog']);
    if ($filters) {
        $selectedArtist = $artists[$artist] ?? null;
        $catalogue = array_values(array_filter(
            $catalogue,
            static fn(array $release): bool =>
                ($selectedArtist === null || dfrCatalogueMatchesArtist($release, $selectedArtist))
                && (!$collaborations || dfrCatalogueIsCollaboration($release, $artists['frenzysax']))
        ));
    }

    usort($catalogue, static fn(array $a, array $b): int => strcmp($b['date'], $a['date']) ?: strcmp($a['id'], $b['id']));
    $count = count($catalogue);
    $pagination = dfrCataloguePage($catalogue, $_GET['page'] ?? 1);
    ob_start();
    ?>
    <main class="mainAlbum">
        <section class="albumCatalog" id="catalog" aria-labelledby="catalogue-title">
            <div class="albumHero catalogueHeading">
                <h1 id="catalogue-title">Nos sorties</h1>
            </div>
            <form class="catalogueFilters" method="get" action="/controller/album.php#catalog" aria-label="Filtres du catalogue">
                <label class="catalogueFilterArtist" for="catalogue-artist">
                    <span>Artiste</span>
                    <select id="catalogue-artist" name="artiste">
                        <option value="">Tous les artistes</option>
                        <?php foreach ($artists as $slug => $entry): ?>
                            <option value="<?= htmlspecialchars($slug) ?>"<?= $artist === $slug ? ' selected' : '' ?>><?= htmlspecialchars($entry['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="catalogueFilterCollaboration">
                    <input type="checkbox" name="collaborations" value="1"<?= $collaborations ? ' checked' : '' ?>>
                    <span>Collaborations uniquement</span>
                </label>
                <div class="catalogueFilterActions">
                    <button type="submit">Filtrer</button>
                    <?php if ($filters): ?>
                        <a href="/controller/album.php#catalog">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
            <?php if ($count === 0): ?>
                <p class="catalogueResults">Aucune sortie ne correspond à cette sélection.</p>
            <?php else: ?>
                <p class="catalogueResults"><?= $count ?> sortie<?= $count > 1 ? 's' : '' ?></p>
                <?php renderCataloguePagination($pagination, 'haut', $filters); ?>
                <div class="albumGrid">
                    <?php foreach ($pagination['items'] as $release): ?>
                        <?= renderReleaseCard($release, true, $spotifyMobileCovers) ?>
                    <?php endforeach; ?>
                </div>
                <?php renderCataloguePagination($pagination, 'bas', $filters); ?>
            <?php endif; ?>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
