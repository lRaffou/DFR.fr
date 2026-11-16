<?php
function renderReleaseCard(array $release, bool $catalogue = false, array $spotifyMobileCovers = [], bool $lead = false): string
{
    $prefix = $catalogue ? 'album' : 'release';
    $cover = $release['image'];
    $mobileCover = $release['mobile_image'] ?? $spotifyMobileCovers[$cover] ?? null;
    if (str_starts_with($cover, '/img/albums/webp/')) {
        $localThumbnail = '/img/albums/webp/mobile/' . basename($cover);
        if (is_file(__DIR__ . '/..' . $localThumbnail)) {
            $mobileCover = $localThumbnail;
        }
    }
    $remoteThumbnail = $mobileCover !== null && str_starts_with($mobileCover, 'https://');
    $spotify = htmlspecialchars($release['spotify']);
    $title = htmlspecialchars($release['title']);
    $artist = htmlspecialchars($release['artist']);
    $listenLabel = htmlspecialchars($release['link_label']);
    ob_start();
    ?>
    <article class="<?= $prefix ?>Card<?= $lead ? ' albumCard--lead' : '' ?>">
        <a class="<?= $prefix ?>Image coverLink" href="<?= $spotify ?>" target="_blank" rel="noopener noreferrer">
            <?php if ($mobileCover !== null): ?>
                <picture>
                    <source media="(max-width: 540px)" srcset="<?= htmlspecialchars($mobileCover) ?>"<?= $remoteThumbnail ? ' data-album-thumbnail' : '' ?>>
            <?php endif; ?>
            <img src="<?= htmlspecialchars($cover) ?>" alt="<?= $title ?> - <?= $artist ?>" <?= $lead ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>
            <?php if ($mobileCover !== null): ?>
                </picture>
            <?php endif; ?>
        </a>
        <div class="<?= $prefix ?>Info">
            <?php if ($lead): ?><p class="albumLeadLabel">Dernière sortie</p><?php endif; ?>
            <h3><?= $title ?></h3>
            <p<?= $catalogue ? ' class="artistName"' : '' ?>><?= $artist ?></p>
            <?php if ($catalogue): ?>
                <p class="releaseDate">
                    <time datetime="<?= htmlspecialchars($release['date']) ?>"><?= dfrReleaseDateLabel($release['date']) ?></time>
                </p>
            <?php endif; ?>
            <a href="<?= $spotify ?>" class="btnListen" target="_blank" rel="noopener noreferrer"
                aria-label="<?= $listenLabel ?> — <?= $title ?> par <?= $artist ?>">
                <i class="fab fa-spotify" aria-hidden="true"></i>
                <?= $listenLabel ?>
            </a>
        </div>
    </article>
    <?php
    return ob_get_clean();
}
