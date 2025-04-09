<?php

function renderAlbum()
{
    ob_start();
    ?>
    <main class="mainAlbum">
        <section class="albumHero">
            <h1>Nos Albums</h1>
            <p>Découvrez nos dernières sorties et notre catalogue musical</p>
        </section>

        <section class="featuredAlbums">
            <h2>Albums à la Une</h2>
            <div class="albumContainer">
                <article class="albumCard featured">
                    <div class="albumImage">
                        <img src="/img/albums/album1.jpg" alt="Album Red Rave">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/album/3qFHJrWeitkjPo1OWmRBHa" class="btnListen"
                                target="_blank">
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Red Rave</h3>
                        <p class="artistName">Artiste: Dao Brothers</p>
                        <p class="releaseDate">Date de sortie: 01/01/2023</p>
                        <div class="albumTags">
                            <span class="tag">Bass House</span>
                            <span class="tag">Techno</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="albumCatalog">
            <h2>Notre Catalogue</h2>
            <div class="albumGrid">
                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album2.jpg" alt="Album Imagination">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/album/0IvMcp0vjpPsioa70rJ0rB" class="btnListen"
                                target="_blank">
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Imagination</h3>
                        <p class="artistName">Artiste: Fernando</p>
                        <p class="releaseDate">Date de sortie: 15/03/2023</p>
                        <div class="albumTags">
                            <span class="tag">Techno</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album3.jpg" alt="Album DJ BABE Event">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/0Jc3ODaHZsJBSvSwQx4Qwm" class="btnListen"
                                target="_blank">
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>DJ BABE Event</h3>
                        <p class="artistName">Artiste: DJ BABE</p>
                        <p class="releaseDate">Date de sortie: 30/06/2023</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album4.jpg" alt="Album Feel Good">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/0Jc3ODaHZsJBSvSwQx4Qwm" class="btnListen"
                                target="_blank">
                                <i class="fab fa-spotify"></i>
                                Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Feel Good</h3>
                        <p class="artistName">Artiste: DJ BABE</p>
                        <p class="releaseDate">Date de sortie: 10/09/2023</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
?>