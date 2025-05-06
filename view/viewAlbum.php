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
                        <img src="/img/albums/album1.png" alt="Album - How Can I">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/221oa36Tj0G8rSX8nbuoyf?si=d53c99e5430a4977"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>How Can I</h3>
                        <p class="artistName">Artiste : Dj Babe</p>
                        <p class="releaseDate">Date de sortie : 01/12/2024</p>
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
                        <img src="/img/albums/album2.jpg" alt="Album - Imagination">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/album/0IvMcp0vjpPsioa70rJ0rB" class="btnListen"
                                target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Imagination</h3>
                        <p class="artistName">Artiste : Fernando Gomez</p>
                        <p class="releaseDate">Date de sortie : 15/09/2024</p>
                        <div class="albumTags">
                            <span class="tag">Techno</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album3.jpg" alt="Album - Red Night">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/album/7nDOtP7tieus0W13J4RCho?si=di0B3QJ2R4Sg6jj_9HFMnQ"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Red Night</h3>
                        <p class="artistName">Artiste : Red Marshal</p>
                        <p class="releaseDate">Date de sortie : 30/06/2022</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album4.jpg" alt="Album - Feel Good">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/0Jc3ODaHZsJBSvSwQx4Qwm?si=91bd0417088d4053"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Feel Good</h3>
                        <p class="artistName">Artiste : Dj Babe</p>
                        <p class="releaseDate">Date de sortie : 10/09/2024</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album5.png" alt="Album - Acid Conversation">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/album/6NCGuVlBWQ3LR0McsRcQ4K?si=u5ghKA_iSj2Kn_HN7E_CTA"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Acid Conversation</h3>
                        <p class="artistName">Artiste : Dao Brothers</p>
                        <p class="releaseDate">Date de sortie : 11/08/2024</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album6.png" alt="Album - Darkness">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/intl-fr/track/0GvGAPCMYmjJVEOZquBC7U?si=99bc00c43d5645e6"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>Darkness</h3>
                        <p class="artistName">Artiste : Fernando Gomez</p>
                        <p class="releaseDate">Date de sortie : 11/05/2024</p>
                        <div class="albumTags">
                            <span class="tag">Tech House</span>
                        </div>
                    </div>
                </article>

                <article class="albumCard">
                    <div class="albumImage">
                        <img src="/img/albums/album7.png" alt="Album - My Eyes">
                        <div class="albumOverlay">
                            <a href="https://open.spotify.com/album/2djAiLCzmUyuqmJ0Fb9Dud?si=26AH8BqXR4eTunfQdZvvXA"
                                class="btnListen" target="_blank">
                                <i class="fab fa-spotify"></i> Écouter sur Spotify
                            </a>
                        </div>
                    </div>
                    <div class="albumInfo">
                        <h3>My Eyes</h3>
                        <p class="artistName">Artiste : Red Marshal</p>
                        <p class="releaseDate">Date de sortie : 21/10/2021</p>
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