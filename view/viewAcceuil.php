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
                        <img src="/img/accueil/Releases-1.png" alt="Red Rave - Red Marshal">
                        <div class="releaseOverlay">
                            <a href="https://dzr.page.link/E5jGMqDgNYxxjR9j6"
                                class="btnListen">
                                <i class="fab fa-deezer"></i> Écouter sur Deezer
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
                        <img src="/img/accueil/Releases-2.png" alt="How Can I - Dj Babe">
                        <div class="releaseOverlay">
                            <a href="https://open.spotify.com/album/47Q6yAPK2TYz133M4TPR9E?si=iQh5YQT-Rpax-mlLr6-gGw"
                                class="btnListen">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="releaseInfo">
                        <h3>How Can I</h3>
                        <p>Dj Babe</p>
                    </div>
                </div>

                <div class="releaseCard">
                    <div class="releaseImage">
                        <img src="/img/accueil/Releases-3.png" alt="Imagination - Fernando Gomez">
                        <div class="releaseOverlay">
                            <a href="https://open.spotify.com/album/29j5GKQGMW4TE54vCICzAL?si=_5MrAjIxQVSJShpLealOSA"
                                class="btnListen">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="releaseInfo">
                        <h3>Imagination</h3>
                        <p>Fernando Gomez</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spotify Section -->
        <section class="spotifySection">
            <h2>Écoutez nos artistes</h2>
            <div class="spotifyPlayer">
                <iframe style="border-radius:12px"
                    src="https://open.spotify.com/embed/playlist/4z6XsMTJ8rsbSGyi20bz9T?utm_source=generator" width="100%"
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