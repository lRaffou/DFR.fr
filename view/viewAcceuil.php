<?php
require_once __DIR__ . '/../components/projects.php';
function renderAcceuil()
{
    $releases = require __DIR__ . '/../model/releases.php';
    $videos = require __DIR__ . '/../model/videos.php';
    $featuredVideo = $videos['recent'][0];
    $playlistId = '4z6XsMTJ8rsbSGyi20bz9T';
    ob_start();
    ?>
    <main class="mainHome">

        <!-- Hero Section -->
        <section class="heroSection">
            <div class="heroContent">
                <h1>Dao Family Records</h1>
                <p>Découvrez l'énergie unique de la musique électronique à travers nos artistes et événements.</p>
                <div class="heroButtons">
                    <a href="/controller/evenement.php" class="btnPrimary">Voir les événements</a>
                    <a href="/controller/artiste.php" class="btnSecondary">Découvrir les artistes</a>
                </div>
            </div>
            <div class="heroVideo">
                <iframe
                    src="https://www.youtube.com/embed/<?= htmlspecialchars($featuredVideo['id']) ?>?playsinline=1"
                    title="<?= htmlspecialchars($featuredVideo['artist'] . ' — ' . $featuredVideo['title'] . ' — Audio officiel') ?>"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            </div>
        </section>

        <div class="homeVideoCaption">
            <p><?= htmlspecialchars($featuredVideo['artist'] . ' — ' . $featuredVideo['title']) ?> · Audio officiel</p>
            <div class="videoLinks">
                <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($featuredVideo['id']) ?>" target="_blank" rel="noopener noreferrer">Écouter sur YouTube</a>
                <a href="/controller/video.php">Toutes les vidéos</a>
            </div>
        </div>

        <?= renderProjects() ?>

        <section class="featuredReleases">
            <h2>À la une</h2>
            <div class="releasesGrid">
                <?php foreach ($releases['featured'] as $release): ?>
                    <article class="releaseCard">
                        <a class="releaseImage coverLink" href="<?= htmlspecialchars($release['spotify']) ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= htmlspecialchars($release['image']) ?>"
                                alt="<?= htmlspecialchars($release['title'] . ' - ' . $release['artist']) ?>"
                                loading="lazy">
                        </a>
                        <div class="releaseInfo">
                            <h3><?= htmlspecialchars($release['title']) ?></h3>
                            <p><?= htmlspecialchars($release['artist']) ?></p>
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
            <p class="catalogLink"><a href="/controller/album.php">Voir toutes les sorties</a></p>
        </section>

        <!-- Spotify Section -->
        <section class="spotifySection">
            <h2>Écoutez nos artistes</h2>
            <p class="catalogLink"><a href="https://open.spotify.com/playlist/<?= $playlistId ?>" target="_blank" rel="noopener noreferrer">Ouvrir la playlist sur Spotify</a></p>
            <div class="spotifyPlayer">
                <iframe style="border-radius:12px"
                    src="https://open.spotify.com/embed/playlist/<?= $playlistId ?>?utm_source=generator" title="Playlist Dao Family Records sur Spotify" width="100%"
                    height="652" frameBorder="0" allowfullscreen=""
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    loading="lazy"></iframe>
            </div>
        </section>

    </main>
    <?php
    return ob_get_clean();
}
?>
