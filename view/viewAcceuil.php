<?php
function renderAcceuil()
{
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
                    src="https://www.youtube.com/embed/videoseries?si=9n7Tnr8qqOqHir4m&amp;list=PLfGk9MSaVom5-wEtYZ2lM2a99SeHHF6sg"
                    title="Dao Family Records"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            </div>
        </section>

        <!-- Featured Releases Section -->
        <section class="featuredReleases">
            <h2>Dernières Sorties</h2>
            <div class="releasesGrid">
                <div class="releaseCard">
                    <div class="releaseImage">
                        <img src="/img/Album_Red_Rave.png" alt="Red Rave - Red Marshal">
                        <div class="releaseOverlay">
                            <a href="https://open.spotify.com/intl-fr/album/3qFHJrWeitkjPo1OWmRBHa" class="btnListen">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="releaseInfo">
                        <h3>Red Rave</h3>
                        <p>Red Marshal</p>
                    </div>
                </div>

                <div class="releaseCard">
                    <div class="releaseImage">
                        <img src="/img/Into_the_club_DaoBrothers.png" alt="Into the Club - Dao Brothers">
                        <div class="releaseOverlay">
                            <a href="https://open.spotify.com/intl-fr/album/0IvMcp0vjpPsioa70rJ0rB" class="btnListen">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="releaseInfo">
                        <h3>Into the Club</h3>
                        <p>Dao Brothers</p>
                    </div>
                </div>

                <div class="releaseCard">
                    <div class="releaseImage">
                        <img src="/img/FeelGood_DJBabe.png" alt="Feel Good - DJ Babe">
                        <div class="releaseOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/0Jc3ODaHZsJBSvSwQx4Qwm" class="btnListen">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="releaseInfo">
                        <h3>Feel Good</h3>
                        <p>DJ Babe</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spotify Section -->
        <section class="spotifySection">
            <h2>Écoutez nos artistes</h2>
            <div class="spotifyPlayer">
                <iframe src="https://open.spotify.com/embed/album/3qFHJrWeitkjPo1OWmRBHa?utm_source=generator&theme=0"
                    width="100%" height="580" frameborder="0" allowfullscreen=""
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
            </div>
        </section>

    </main>
    <?php
    return ob_get_clean();
}
?>