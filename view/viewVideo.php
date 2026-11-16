<?php
require_once __DIR__ . '/../components/media.php';

function renderVideo()
{
    $videos = require __DIR__ . '/../model/videos.php';
    $featured = $videos['recent'][0];
    $galleries = [];
    foreach (['recent' => 'Sorties récentes', 'collection' => 'Clips et lives'] as $section => $heading) {
        $items = array_filter($videos[$section], static fn(array $video): bool => $video['id'] !== $featured['id']);
        if ($items) {
            $galleries[$section] = ['heading' => $heading, 'items' => $items];
        }
    }
    ob_start();
    ?>
    <main class="mainVideo">
        <section class="videoHero">
            <h1>Nos vidéos</h1>
            <p>Les clips, les lives et les audios officiels des artistes du label</p>
            <div class="videoLinks">
                <a href="<?= htmlspecialchars($videos['channel']) ?>" target="_blank" rel="noopener noreferrer">La chaîne DFR</a>
                <a href="<?= htmlspecialchars($videos['playlist']) ?>" target="_blank" rel="noopener noreferrer">La playlist clips et lives</a>
            </div>
            <?php if ($galleries): ?>
                <nav class="videoSectionLinks" aria-label="Sections vidéos">
                    <?php foreach ($galleries as $section => $gallery): ?>
                        <a href="#video-<?= $section ?>"><?= $gallery['heading'] ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </section>

        <section class="featuredVideo">
            <h2>À la une</h2>
            <div class="videoContainer">
                <article class="video">
                    <?= renderYouTubePreview($featured) ?>
                </article>
                <div class="videoInfo">
                    <span class="videoFormat">Audio officiel</span>
                    <h3><?= htmlspecialchars($featured['title']) ?></h3>
                    <p><?= htmlspecialchars($featured['artist']) ?> · Sortie le <time datetime="<?= $featured['date'] ?>"><?= (new DateTimeImmutable($featured['date']))->format('d/m/Y') ?></time></p>
                    <p class="videoLinks"><a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($featured['id']) ?>" target="_blank" rel="noopener noreferrer">Écouter sur YouTube</a></p>
                </div>
            </div>
        </section>

        <?php foreach ($galleries as $section => $gallery): ?>
            <section class="videoGallery" id="video-<?= $section ?>">
                <h2><?= $gallery['heading'] ?></h2>
                <div class="videoGrid">
                    <?php foreach ($gallery['items'] as $video): ?>
                        <?php $label = $video['artist'] . ' — ' . $video['title']; ?>
                        <article class="videoCard" data-video="<?= htmlspecialchars($video['id']) ?>" data-title="<?= htmlspecialchars($label) ?>">
                            <div class="videoThumbnail">
                                <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['id']) ?>/hqdefault.jpg"
                                    loading="lazy" decoding="async" width="480" height="360" alt="<?= htmlspecialchars($label) ?>">
                                <button type="button" class="playButton" aria-label="<?= htmlspecialchars('Lire ' . $label) ?>">
                                    <i class="fas fa-play" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="videoDetails">
                                <span class="videoFormat"><?= htmlspecialchars($video['format'] ?? 'Audio officiel') ?></span>
                                <h3><?= htmlspecialchars($video['title']) ?></h3>
                                <p><?= htmlspecialchars($video['artist']) ?></p>
                                <?php if (isset($video['date'])): ?>
                                    <p>Sortie le <time datetime="<?= $video['date'] ?>"><?= (new DateTimeImmutable($video['date']))->format('d/m/Y') ?></time></p>
                                <?php endif; ?>
                                <p class="videoExternalLink"><a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($video['id']) ?>"
                                    target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars('Voir ' . $label . ' sur YouTube') ?>">Voir sur YouTube</a></p>
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
