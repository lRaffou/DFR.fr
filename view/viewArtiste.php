<?php
require_once __DIR__ . '/../components/artist-modal.php';

function renderArtiste(array $artists, ?string $profileId = null): string
{
    $profile = $profileId === null ? null : $artists[$profileId];
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $artistCardSizes = '(min-width: 1264px) 282px, (min-width: 1200px) 23vw, (min-width: 900px) 31vw, (min-width: 560px) 46vw, clamp(92px, 28vw, 132px)';
    $heroArtist = array_key_first($artists);
    ob_start();
    ?>
    <main class="mainArtist editorialPage">

        <header class="editorialHeader">
            <h1>Nos artistes</h1>
            <p>Les DJ, producteurs et duos de Dao Family Records.</p>
        </header>

        <?php if ($profile !== null): ?>
            <article id="artistPublicProfile" class="artistPublicProfile" data-artist="<?= $escape($profileId) ?>" aria-labelledby="artistPublicName">
                <div class="artistPublicPortrait">
                    <img src="<?= $escape($profile['image']) ?>" alt="Portrait de <?= $escape($profile['name']) ?>" decoding="async">
                </div>
                <div class="artistPublicContent">
                    <h2 id="artistPublicName"><?= $escape($profile['name']) ?></h2>
                    <p class="artistPublicRole"><?= $escape($profile['role']) ?></p>
                    <div class="artistGenres">
                        <?php foreach ($profile['genres'] as $genre): ?>
                            <span class="artistGenre"><?= $escape($genre) ?></span>
                        <?php endforeach; ?>
                    </div>
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
                <a class="artistCard" href="?artiste=<?= $escape($artist['slug'] ?? $id) ?>" aria-haspopup="dialog" data-artist-modal data-artist="<?= $escape($id) ?>">
                    <div class="artistImage">
                        <?php if ($cardImage !== null): ?>
                            <picture>
                                <source media="(max-width: 768px)" srcset="<?= $escape($mobileSrcset) ?>" sizes="<?= $artistCardSizes ?>">
                        <?php endif; ?>
                        <img src="<?= $escape($cardImage['desktop']['src'] ?? $artist['image']) ?>"
                            <?php if ($cardImage !== null): ?>
                                srcset="<?= $escape($desktopSrcset) ?>" sizes="<?= $artistCardSizes ?>"
                            <?php endif; ?>
                            alt="" <?= $id === $heroArtist ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>
                        <?php if ($cardImage !== null): ?>
                            </picture>
                        <?php endif; ?>
                    </div>
                    <div class="artistInfo">
                        <div class="artistCardTitle">
                            <h2><?= $escape($artist['name']) ?></h2>
                            <span aria-hidden="true">→</span>
                        </div>
                        <p><?= $escape($artist['role']) ?></p>
                        <div class="artistGenres">
                            <?php foreach ($artist['genres'] as $genre): ?>
                                <span class="artistGenre"><?= $escape($genre) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>

        <?php renderArtistModal($artists); ?>
    </main>
    <?php
    return ob_get_clean();
}
