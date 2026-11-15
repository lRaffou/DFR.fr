<?php
function renderArtiste()
{
    ob_start();
    ?>
    <main class="mainArtist">

        <section class="artistHero">
            <h1>Nos Artistes</h1>
            <p>Découvrez les talents qui font vibrer Dao Family Records</p>
        </section>

        <section class="artistGrid">

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="red-marshal">
                <div class="artistImage">
                    <img src="/img/artistes/artist1.png" alt="Red Marshal">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Red Marshal</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Hard Techno / Hardfonk</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="dj-babe">
                <div class="artistImage">
                    <img src="/img/artistes/artist2.png" alt="Dj Babe">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Dj Babe</h2>
                            <p>DJ - Productrice</p>
                            <span class="artistGenre">Hard Techno</span>
                            <span class="artistGenre">Tech House</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="fernando">
                <div class="artistImage">
                    <img src="/img/artistes/artist3.png" alt="Fernando Gomez">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Fernando Gomez</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Techno</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="dao-brothers">
                <div class="artistImage">
                    <img src="/img/artistes/artist4.png" alt="Dao Brothers">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Dao Brothers</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Bass House</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="zoe-kunter">
                <div class="artistImage">
                    <img src="/img/artistes/artist5.png" alt="Zoé Kunter">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Zoé Kunter</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Techno</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="clarity">
                <div class="artistImage">
                    <img src="/img/artistes/artist6.png" alt="Clarity">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Clarity</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Hard Techno
                            </span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="frenzysax">
                <div class="artistImage">
                    <img src="/img/artistes/frenzysax.jpg" alt="Frenzysax" loading="lazy">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Frenzysax</h2>
                            <p>Collaboration DFR</p>
                            <span class="artistGenre">Musique électronique</span>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <!-- Modal pour afficher les détails de l'artiste -->
        <div id="artistModal" class="artistModal" role="dialog" aria-modal="true" aria-label="Fiche artiste">
            <div class="modalContent">
                <button type="button" class="closeModal" aria-label="Fermer la fiche artiste">&times;</button>
                <div id="artistDetailsContainer"></div>
            </div>
        </div>

    </main>
    <?php
    return ob_get_clean();
}
?>
