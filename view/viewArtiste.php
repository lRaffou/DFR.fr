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
                    <img src="/img/DJ_BABE_01.png" alt="DJ Babe" loading="lazy">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>DJ Babe</h2>
                            <p>DJ - Productrice</p>
                            <span class="artistGenre">Hard Techno</span>
                            <span class="artistGenre">Tech House</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="fernando">
                <div class="artistImage">
                    <img src="https://i.scdn.co/image/ab6761610000e5ebb7dbded7789bb71036f6b48d" alt="Fernando Gomez">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Fernando Gomez</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Techno / Hard Techno</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="dao-brothers">
                <div class="artistImage">
                    <img src="https://i.scdn.co/image/ab6761610000e5eb4994aa0e261ae445251ef760" alt="Dao Brothers" loading="lazy">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Dao Brothers</h2>
                            <p>Duo de DJs et producteurs</p>
                            <span class="artistGenre">Bass House</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="zoe-kunter">
                <div class="artistImage">
                    <img src="/img/gallerie/artist5_1on4.jpg" alt="Zoé Kunter" loading="lazy">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Zoé Kunter</h2>
                            <p>DJ</p>
                            <span class="artistGenre">Techno</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="artistCard" role="button" tabindex="0" aria-haspopup="dialog" data-artist="clarity">
                <div class="artistImage">
                    <img src="/img/gallerie/artist6_1on4.jpg" alt="Clarity" loading="lazy">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Clarity</h2>
                            <p>DJ</p>
                            <span class="artistGenre">Hard Techno</span>
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
                            <p>Duo · Clarisax et Red Marshal</p>
                            <span class="artistGenre">Électro &amp; saxophone live</span>
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
