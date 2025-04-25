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
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
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
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
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
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
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

        <!-- Next Event Section -->
        <section class="nextEvent">
            <h2>Prochain Événement</h2>
            <div class="eventContent">
                <div class="eventCard">
                    <div class="eventImage">
                        <img src="/img/nextEvent.jpg" alt="Coeur Meurtri - Open Air">
                        <div class="eventDate">
                            <span class="day">10 - 11</span>
                            <span class="month">MAI</span>
                        </div>
                    </div>
                    <div class="eventInfo">
                        <h3>Coeur Meurtri - Open Air</h3>
                        <div class="eventTags">
                            <span class="tag">Bass House</span>
                            <span class="tag">Techno</span>
                            <span class="tag">Hard Techno</span>
                        </div>
                        <p>"Cœur Meurtri by DJ Babe" est un événement musical qui réunira exclusivement des artistes
                            féminines, mettant en lumière leur créativité, leur diversité et leur force. Imaginé par Sophie
                            Dao,
                            alias DJ Babe, talent émergent toulousaine, cet événement vous garanti une expérience
                            inoubliable.
                            Ce projet incarne une vision inclusive et bienveillante, célébrant la richesse artistique et
                            culturelle des femmes dans la musique.
                            Le cœur battra plus fort au rythme de COEUR MEURTRI, l'open air 100 % féminin orchestré par DJ
                            Babe ! Pendant deux jours, viens vibrer dans une ambiance électrique et bienveillante, en plein
                            air au Port de l'Embouchure à Toulouse.</p>
                        <a href="https://fb.me/e/7OXhf42dF" target="_blank" class="btnEvent">Réserver maintenant</a>
                        <h3>Coeur Meurtri - After officiel open air</h3>
                        <div class="eventTags">
                            <span class="tag">Bass House</span>
                            <span class="tag">Techno</span>
                            <span class="tag">Hard Techno</span>
                        </div>
                        <p>Une nuit explosive de musique électronique avec les meilleurs artistes de la scène.</p>
                        <a href="https://shotgun.live/fr/events/after-officiel-coeur-meurtri-open-air-2025" target="_blank"
                            class="btnEvent">Réserver maintenant</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Artists Section -->
        <section class="featuredArtists">
            <h2>Nos Artistes</h2>
            <div class="artistsGrid">
                <div class="artistCard">
                    <div class="artistImage">
                        <img src="img\artistes\artist1.png" alt="Red Marshal">
                    </div>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h3>Red Marshal</h3>
                            <p>Hard Techno</p>
                        </div>
                    </div>
                </div>
                <div class="artistCard">
                    <div class="artistImage">
                        <img src="img\artistes\artist2.png" alt="DJ Babe">
                    </div>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h3>DJ Babe</h3>
                            <p>Tech House</p>
                        </div>
                    </div>
                </div>
                <div class="artistCard">
                    <div class="artistImage">
                        <img src="img\artistes\artist3.png" alt="Fernando Gomez">
                    </div>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h3>Fernando Gomez</h3>
                            <p>Techno</p>
                        </div>
                    </div>
                </div>
                <div class="artistCard">
                    <div class="artistImage">
                        <img src="img\artistes\artist4.png" alt="Dao Brothers">
                    </div>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h3>Dao Brothers</h3>
                            <p>Bass House</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spotify Section -->
        <section class="spotifySection">
            <h2>Écoutez nos artistes</h2>
            <div class="spotifyPlayer">
                <iframe src="https://open.spotify.com/embed/album/3qFHJrWeitkjPo1OWmRBHa?utm_source=generator&theme=0"
                    width="100%" height="380" frameBorder="0" allowfullscreen=""
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
            </div>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
?>