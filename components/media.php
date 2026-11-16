<?php
function renderYouTubePreview(array $video, bool $eager = true): string
{
    $id = rawurlencode($video['id']);
    $title = htmlspecialchars($video['artist'] . ' — ' . $video['title'], ENT_QUOTES, 'UTF-8');
    ob_start();
    ?>
    <a class="youtubePreview" href="https://www.youtube.com/watch?v=<?= $id ?>"
        data-player-src="https://www.youtube.com/embed/<?= $id ?>?autoplay=1&amp;playsinline=1"
        data-player-title="<?= $title ?>"
        data-player-allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        target="_blank" rel="noopener noreferrer" aria-label="Lire <?= $title ?>">
        <img src="https://img.youtube.com/vi/<?= $id ?>/hqdefault.jpg" alt=""
            width="480" height="360" <?= $eager ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>
        <span class="youtubePreviewPlay" aria-hidden="true">▶</span>
    </a>
    <?php
    return ob_get_clean();
}
