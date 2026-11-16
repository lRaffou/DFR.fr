<?php
require_once __DIR__ . '/../components/media.php';

function renderVideo()
{
    $videos = require __DIR__ . '/../model/videos.php';
    $featured = $videos['recent'][0];
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $galleries = [];
    foreach (['recent' => 'Sorties récentes', 'collection' => 'Clips et lives'] as $section => $heading) {
        $items = array_filter($videos[$section], static fn(array $video): bool => $video['id'] !== $featured['id']);
        if ($items) {
            $galleries[$section] = ['heading' => $heading, 'items' => $items];
        }
    }
    ob_start();
    ?>
    <main class="mainVideo editorialPage">
        <header class="editorialHeader">
            <h1>Nos vidéos</h1>
            <p>Les clips, les lives et les audios officiels du label.</p>
            <?php if ($galleries): ?>
                <nav class="videoSectionLinks" aria-label="Sections vidéos">
                    <?php foreach ($galleries as $section => $gallery): ?>
                        <a href="#video-<?= $section ?>"><?= $gallery['heading'] ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </header>

        <section class="videoFeature" aria-labelledby="featured-video-title">
            <div class="editorialSectionHeading">
                <h2 id="featured-video-title">À la une</h2>
                <a href="<?= $escape($videos['channel']) ?>" target="_blank" rel="noopener noreferrer">Chaîne YouTube DFR <span aria-hidden="true">↗</span></a>
            </div>
            <article class="videoFeaturePanel">
                <div class="videoFeatureMedia"><?= renderYouTubePreview($featured) ?></div>
                <div class="videoFeatureCopy">
                    <p class="videoFormat">Audio officiel</p>
                    <h3><?= $escape($featured['title']) ?></h3>
                    <p class="videoCredits"><?= $escape($featured['artist']) ?> · Sortie le <time datetime="<?= $escape($featured['date']) ?>"><?= (new DateTimeImmutable($featured['date']))->format('d/m/Y') ?></time></p>
                    <a class="videoFeatureLink" href="https://www.youtube.com/watch?v=<?= rawurlencode($featured['id']) ?>" target="_blank" rel="noopener noreferrer">Écouter sur YouTube <span aria-hidden="true">↗</span></a>
                </div>
            </article>
        </section>

        <?php foreach ($galleries as $section => $gallery): ?>
            <section class="videoGallery" id="video-<?= $section ?>" aria-labelledby="video-<?= $section ?>-title">
                <div class="editorialSectionHeading">
                    <h2 id="video-<?= $section ?>-title"><?= $gallery['heading'] ?></h2>
                    <?php if ($section === 'collection'): ?>
                        <a href="<?= $escape($videos['playlist']) ?>" target="_blank" rel="noopener noreferrer">Playlist clips et lives <span aria-hidden="true">↗</span></a>
                    <?php endif; ?>
                </div>
                <div class="videoGrid">
                    <?php foreach ($gallery['items'] as $video): ?>
                        <?php $label = $video['artist'] . ' — ' . $video['title']; ?>
                        <article class="videoCard" data-video="<?= $escape($video['id']) ?>" data-title="<?= $escape($label) ?>">
                            <div class="videoThumbnail">
                                <img src="https://img.youtube.com/vi/<?= rawurlencode($video['id']) ?>/hqdefault.jpg"
                                    loading="lazy" decoding="async" width="480" height="360" alt="">
                                <button type="button" class="playButton" aria-label="<?= $escape('Lire ' . $label) ?>">
                                    <i class="fas fa-play" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="videoDetails">
                                <span class="videoFormat"><?= $escape($video['format'] ?? 'Audio officiel') ?></span>
                                <h3><?= $escape($video['title']) ?></h3>
                                <p><?= $escape($video['artist']) ?></p>
                                <?php if (isset($video['date'])): ?>
                                    <p>Sortie le <time datetime="<?= $escape($video['date']) ?>"><?= (new DateTimeImmutable($video['date']))->format('d/m/Y') ?></time></p>
                                <?php endif; ?>
                                <p class="videoExternalLink"><a href="https://www.youtube.com/watch?v=<?= rawurlencode($video['id']) ?>"
                                    target="_blank" rel="noopener noreferrer" aria-label="<?= $escape('Voir ' . $label . ' sur YouTube') ?>">Voir sur YouTube <span aria-hidden="true">↗</span></a></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <div id="videoModal" class="modal" role="dialog" aria-modal="true" aria-label="Lecteur vidéo">
            <div class="modalContent">
                <div class="videoModalBar">
                    <a id="modalYoutubeLink" target="_blank" rel="noopener noreferrer">Ouvrir sur YouTube</a>
                    <button type="button" class="closeModal" aria-label="Fermer la vidéo">&times;</button>
                </div>
                <div class="videoContainer">
                    <article class="video">
                        <iframe id="modalVideo" width="560" height="315" title="Vidéo de Dao Family Records"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </article>
                </div>
            </div>
        </div>
    </main>
    <?php
    return ob_get_clean();
}
