<?php
require_once __DIR__ . '/../components/media.php';
require_once __DIR__ . '/../components/projects.php';
require_once __DIR__ . '/../components/releases.php';
require_once __DIR__ . '/../model/site.php';
function renderAcceuil(string $nextEvent)
{
    $releases = require __DIR__ . '/../model/releases.php';
    $videos = require __DIR__ . '/../model/videos.php';
    $videoSelection = [$videos['recent'][0]];
    foreach ($videos['collection'] as $video) {
        if ($video['id'] === 'AAEuRLXiaZE') {
            $videoSelection[] = $video;
            break;
        }
    }
    $site = dfrSite();
    $playlistUrl = htmlspecialchars($site['social']['Spotify']['url']);
    $spotifyMobileCovers = require __DIR__ . '/../model/album-mobile-covers.php';
    ob_start();
    ?>
    <main class="mainHome">

        <section class="homeHero" aria-labelledby="home-title">
            <div class="homeIntro">
                <h1 id="home-title">Dao Family Records</h1>
                <p>Label de musique électronique à Toulouse, de la bass house à la hard techno.</p>
                <div class="homeActions">
                    <a href="#playlist-dfr" class="btnPrimary">Écouter la playlist</a>
                    <a href="/controller/evenement.php" class="btnSecondary">Voir les événements</a>
                </div>
            </div>
            <a class="homeArtistFeature" href="/controller/artiste.php?artiste=red-marshal" aria-label="Découvrir Red Marshal">
                <img src="/img/gallerie/webp/red-marshal-2.webp" alt="" width="840" height="473" fetchpriority="high">
                <span>Red Marshal <span aria-hidden="true">→</span></span>
            </a>
        </section>

        <?= $nextEvent ?>

        <section class="homeSection" aria-labelledby="home-releases-title">
            <div class="homeSectionHeading">
                <h2 id="home-releases-title">À la une</h2>
                <a href="/controller/album.php">Toutes les sorties <span aria-hidden="true">→</span></a>
            </div>
            <div class="releasesGrid">
                <?php foreach ($releases['featured'] as $release): ?>
                    <?= renderReleaseCard($release, spotifyMobileCovers: $spotifyMobileCovers) ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="homeSection homePlaylist" id="playlist-dfr" aria-labelledby="home-playlist-title">
            <div class="homeSectionHeading">
                <h2 id="home-playlist-title">Écoutez nos artistes</h2>
                <a href="<?= $playlistUrl ?>" target="_blank" rel="noopener noreferrer">Ouvrir sur Spotify <span aria-hidden="true">↗</span></a>
            </div>
            <div class="spotifyPlayer">
                <a class="spotifyPreview" href="<?= $playlistUrl ?>"
                    data-player-src="https://open.spotify.com/embed/playlist/<?= DFR_SPOTIFY_PLAYLIST ?>"
                    data-player-title="Playlist Dao Family Records sur Spotify"
                    data-player-allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    target="_blank" rel="noopener noreferrer"><i class="fab fa-spotify" aria-hidden="true"></i> Afficher le lecteur Spotify</a>
            </div>
        </section>

        <section class="homeSection" aria-labelledby="home-videos-title">
            <div class="homeSectionHeading">
                <h2 id="home-videos-title">Vidéos &amp; sets</h2>
                <a href="/controller/video.php">Toutes les vidéos <span aria-hidden="true">→</span></a>
            </div>
            <div class="homeVideos">
                <?php foreach ($videoSelection as $video): ?>
                    <article class="homeVideo">
                        <div class="homeVideoPlayer"><?= renderYouTubePreview($video, eager: false) ?></div>
                        <div class="homeVideoInfo">
                            <h3><a href="https://www.youtube.com/watch?v=<?= rawurlencode($video['id']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($video['title']) ?></a></h3>
                            <p><?= htmlspecialchars($video['artist'] . ' · ' . ($video['format'] ?? 'Audio officiel')) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <?= renderHomeProjects(require __DIR__ . '/../model/projects.php') ?>

    </main>
    <?php
    return ob_get_clean();
}
?>
