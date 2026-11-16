<?php
function renderArtiste(array $artists, ?string $profileId = null): string
{
    $profile = $profileId === null ? null : $artists[$profileId];
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $artistCardSizes = '(min-width: 1264px) 282px, (min-width: 1200px) 23vw, (min-width: 1000px) 30vw, (min-width: 624px) 45vw, calc(100vw - 2rem)';
    $heroArtist = array_key_first($artists);
    $profiles = array_map(static function (array $artist): array {
        unset($artist['slug'], $artist['card_image'], $artist['press_bio']);
        return $artist;
    }, $artists);
    ob_start();
    ?>
    <main class="mainArtist">

        <section class="artistHero">
            <h1>Nos Artistes</h1>
            <p>Découvrez les talents qui font vibrer Dao Family Records</p>
        </section>

        <?php if ($profile !== null): ?>
            <article id="artistPublicProfile" class="artistPublicProfile" data-artist="<?= $escape($profileId) ?>" aria-labelledby="artistPublicName">
                <div class="artistPublicPortrait">
                    <img src="<?= $escape($profile['image']) ?>" alt="Portrait de <?= $escape($profile['name']) ?>" decoding="async">
                </div>
                <div class="artistPublicContent">
                    <h2 id="artistPublicName"><?= $escape($profile['name']) ?></h2>
                    <p class="artistPublicRole"><?= $escape($profile['role']) ?> · <?= $escape(implode(' / ', $profile['genres'])) ?></p>
                    <?php foreach ($profile['bio'] as $paragraph): ?>
                        <p><?= $escape($paragraph) ?></p>
                    <?php endforeach; ?>
                    <div class="artistPublicLinks">
                        <?php if (!empty($profile['social_spotify'])): ?>
                            <a href="<?= $escape($profile['social_spotify']) ?>" target="_blank" rel="noopener noreferrer">Spotify</a>
                        <?php endif; ?>
                        <?php if (!empty($profile['social_youtube'])): ?>
                            <a href="<?= $escape($profile['social_youtube']) ?>" target="_blank" rel="noopener noreferrer">YouTube</a>
                        <?php endif; ?>
                        <a href="<?= $escape($profile['social_instagram']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
                        <?php if (!empty($profile['social_spotify']) || !empty($profile['release'])): ?>
                            <a href="/controller/album.php?artiste=<?= $escape(rawurlencode($profile['slug'] ?? $profileId)) ?>#catalog">Toutes ses sorties</a>
                        <?php endif; ?>
                        <a href="/controller/booking.php">Booking &amp; Presse</a>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <section class="artistGrid">
            <?php foreach ($artists as $id => $artist): ?>
                <?php
                $cardImage = $artist['card_image'] ?? null;
                if ($cardImage !== null) {
                    $baseSrcset = $artist['image'] . ' ' . $cardImage['width'] . 'w, ';
                    $mobileSrcset = $baseSrcset . $cardImage['mobile']['src'] . ' ' . $cardImage['mobile']['width'] . 'w';
                    $desktopSrcset = $baseSrcset . $cardImage['desktop']['src'] . ' ' . $cardImage['desktop']['width'] . 'w';
                }
                ?>
                <a class="artistCard" href="?artiste=<?= $escape($artist['slug'] ?? $id) ?>" aria-haspopup="dialog" data-artist="<?= $escape($id) ?>">
                    <div class="artistImage">
                        <?php if ($cardImage !== null): ?>
                            <picture>
                                <source media="(max-width: 768px)" srcset="<?= $escape($mobileSrcset) ?>" sizes="<?= $artistCardSizes ?>">
                        <?php endif; ?>
                        <img src="<?= $escape($cardImage['desktop']['src'] ?? $artist['image']) ?>"
                            <?php if ($cardImage !== null): ?>
                                srcset="<?= $escape($desktopSrcset) ?>" sizes="<?= $artistCardSizes ?>"
                            <?php endif; ?>
                            alt="<?= $escape($artist['name']) ?>" <?= $id === $heroArtist ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>
                        <?php if ($cardImage !== null): ?>
                            </picture>
                        <?php endif; ?>
                        <div class="artistOverlay">
                            <div class="artistInfo">
                                <h2><?= $escape($artist['name']) ?></h2>
                                <p><?= $escape($artist['role']) ?></p>
                                <?php foreach ($artist['genres'] as $genre): ?>
                                    <span class="artistGenre"><?= $escape($genre) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>

        <div id="artistModal" class="artistModal" role="dialog" aria-modal="true" aria-label="Fiche artiste">
            <div class="modalContent">
                <div class="artistModalBar">
                    <button type="button" class="closeModal" aria-label="Fermer la fiche artiste">&times;</button>
                </div>
                <div id="artistDetailsContainer"></div>
            </div>
        </div>

        <script type="application/json" id="artistData"><?= json_encode($profiles, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    </main>
    <?php
    return ob_get_clean();
}
