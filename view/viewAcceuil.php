<?php
require_once __DIR__ . '/../components/media.php';
require_once __DIR__ . '/../components/projects.php';
require_once __DIR__ . '/../components/releases.php';
require_once __DIR__ . '/../model/site.php';
function renderAcceuil(string $nextEvent)
{
    $releases = require __DIR__ . '/../model/releases.php';
    $videos = require __DIR__ . '/../model/videos.php';
    $featuredVideo = $videos['recent'][0];
    $site = dfrSite();
    $playlistUrl = htmlspecialchars($site['social']['Spotify']['url']);
    ob_start();
    ?>
    <main class="mainHome">

        <section class="heroSection">
            <div class="heroContent">
                <h1>Dao Family Records</h1>
                <p>Label de musique électronique à Toulouse, de la bass house à la hard techno.</p>
                <div class="heroButtons">
                    <a href="/controller/evenement.php" class="btnPrimary">Voir les événements</a>
                    <a href="/controller/artiste.php" class="btnSecondary">Découvrir les artistes</a>
                </div>
            </div>
            <div class="heroMedia">
                <div class="heroVideo">
                    <?= renderYouTubePreview($featuredVideo) ?>
                </div>
                <div class="homeVideoCaption">
                    <p><?= htmlspecialchars($featuredVideo['artist'] . ' — ' . $featuredVideo['title']) ?> · Audio officiel</p>
                    <div class="videoLinks">
                        <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($featuredVideo['id']) ?>" target="_blank" rel="noopener noreferrer">Écouter sur YouTube</a>
                        <a href="/controller/video.php">Toutes les vidéos</a>
                    </div>
                </div>
            </div>
        </section>

        <?= $nextEvent ?>
        <?= renderHomeProjects(require __DIR__ . '/../model/projects.php') ?>

        <section class="featuredReleases">
            <h2>À la une</h2>
            <div class="releasesGrid">
                <?php foreach ($releases['featured'] as $release): ?>
                    <?= renderReleaseCard($release) ?>
                <?php endforeach; ?>
            </div>
            <p class="catalogLink"><a href="/controller/album.php">Voir toutes les sorties</a></p>
        </section>

        <!-- Spotify Section -->
        <section class="spotifySection">
            <h2>Écoutez nos artistes</h2>
            <p class="catalogLink"><a href="<?= $playlistUrl ?>" target="_blank" rel="noopener noreferrer">Ouvrir la playlist sur Spotify</a></p>
            <div class="spotifyPlayer">
                <a class="spotifyPreview" href="<?= $playlistUrl ?>"
                    data-player-src="https://open.spotify.com/embed/playlist/<?= DFR_SPOTIFY_PLAYLIST ?>"
                    data-player-title="Playlist Dao Family Records sur Spotify"
                    data-player-allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    target="_blank" rel="noopener noreferrer"><i class="fab fa-spotify" aria-hidden="true"></i> Afficher le lecteur Spotify</a>
            </div>
        </section>

    </main>
    <?php
    return ob_get_clean();
}
?>
