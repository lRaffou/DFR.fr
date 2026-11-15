<?php
function renderArtiste()
{
    $artistCardSizes = '(min-width: 1264px) 282px, (min-width: 1200px) 23vw, (min-width: 1000px) 30vw, (min-width: 624px) 45vw, calc(100vw - 2rem)';
    ob_start();
    ?>
    <main class="mainArtist">

        <section class="artistHero">
            <h1>Nos Artistes</h1>
            <p>Découvrez les talents qui font vibrer Dao Family Records</p>
        </section>

        <section class="artistGrid">

            <a class="artistCard" href="?artiste=red-marshal" aria-haspopup="dialog" data-artist="red-marshal">
                <div class="artistImage">
                    <picture>
                        <source media="(max-width: 768px)"
                            srcset="/img/artistes/webp/red-marshal-640.webp 640w, /img/artistes/webp/red-marshal-mobile.webp 900w"
                            sizes="<?= $artistCardSizes ?>">
                        <img src="/img/artistes/webp/red-marshal.webp"
                            srcset="/img/artistes/webp/red-marshal-640.webp 640w, /img/artistes/webp/red-marshal.webp 1000w"
                            sizes="<?= $artistCardSizes ?>" alt="Red Marshal" fetchpriority="high">
                    </picture>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Red Marshal</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Hard Techno / Hardfonk</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=dj-babe" aria-haspopup="dialog" data-artist="dj-babe">
                <div class="artistImage">
                    <picture>
                        <source media="(max-width: 768px)"
                            srcset="/img/artistes/webp/dj-babe-420.webp 420w, /img/artistes/webp/dj-babe-mobile.webp 600w"
                            sizes="<?= $artistCardSizes ?>">
                        <img src="/img/artistes/webp/dj-babe.webp"
                            srcset="/img/artistes/webp/dj-babe-420.webp 420w, /img/artistes/webp/dj-babe.webp 633w"
                            sizes="<?= $artistCardSizes ?>" alt="DJ Babe" loading="lazy" decoding="async">
                    </picture>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>DJ Babe</h2>
                            <p>DJ - Productrice</p>
                            <span class="artistGenre">Hard Techno</span>
                            <span class="artistGenre">Tech House</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=fernando-gomez" aria-haspopup="dialog" data-artist="fernando">
                <div class="artistImage">
                    <img src="https://i.scdn.co/image/ab6761610000e5ebb7dbded7789bb71036f6b48d" alt="Fernando Gomez" loading="lazy" decoding="async">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Fernando Gomez</h2>
                            <p>DJ - Producteur</p>
                            <span class="artistGenre">Techno / Hard Techno</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=dao-brothers" aria-haspopup="dialog" data-artist="dao-brothers">
                <div class="artistImage">
                    <img src="https://i.scdn.co/image/ab6761610000e5eb4994aa0e261ae445251ef760" alt="Dao Brothers" loading="lazy" decoding="async">
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Dao Brothers</h2>
                            <p>Duo de DJs et producteurs</p>
                            <span class="artistGenre">Bass House</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=zoe-kunter" aria-haspopup="dialog" data-artist="zoe-kunter">
                <div class="artistImage">
                    <picture>
                        <source media="(max-width: 768px)"
                            srcset="/img/artistes/webp/zoe-kunter-420.webp 420w, /img/artistes/webp/zoe-kunter-mobile.webp 640w"
                            sizes="<?= $artistCardSizes ?>">
                        <img src="/img/artistes/webp/zoe-kunter.webp"
                            srcset="/img/artistes/webp/zoe-kunter-420.webp 420w, /img/artistes/webp/zoe-kunter.webp 775w"
                            sizes="<?= $artistCardSizes ?>" alt="Zoé Kunter" loading="lazy" decoding="async">
                    </picture>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Zoé Kunter</h2>
                            <p>DJ</p>
                            <span class="artistGenre">Techno</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=clarity" aria-haspopup="dialog" data-artist="clarity">
                <div class="artistImage">
                    <picture>
                        <source media="(max-width: 768px)"
                            srcset="/img/artistes/webp/clarity-420.webp 420w, /img/artistes/webp/clarity-mobile.webp 540w"
                            sizes="<?= $artistCardSizes ?>">
                        <img src="/img/artistes/webp/clarity.webp"
                            srcset="/img/artistes/webp/clarity-420.webp 420w, /img/artistes/webp/clarity.webp 562w"
                            sizes="<?= $artistCardSizes ?>" alt="Clarity" loading="lazy" decoding="async">
                    </picture>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Clarity</h2>
                            <p>DJ</p>
                            <span class="artistGenre">Hard Techno</span>
                        </div>
                    </div>
                </div>
            </a>

            <a class="artistCard" href="?artiste=frenzysax" aria-haspopup="dialog" data-artist="frenzysax">
                <div class="artistImage">
                    <picture>
                        <source media="(max-width: 768px)"
                            srcset="/img/artistes/webp/frenzysax-420.webp 420w, /img/artistes/webp/frenzysax-mobile.webp 600w"
                            sizes="<?= $artistCardSizes ?>">
                        <img src="/img/artistes/webp/frenzysax.webp"
                            srcset="/img/artistes/webp/frenzysax-420.webp 420w, /img/artistes/webp/frenzysax.webp 640w"
                            sizes="<?= $artistCardSizes ?>" alt="Frenzysax" loading="lazy" decoding="async">
                    </picture>
                    <div class="artistOverlay">
                        <div class="artistInfo">
                            <h2>Frenzysax</h2>
                            <p>Duo · Clarisax et Red Marshal</p>
                            <span class="artistGenre">Électro &amp; saxophone live</span>
                        </div>
                    </div>
                </div>
            </a>
        </section>

        <!-- Modal pour afficher les détails de l'artiste -->
        <div id="artistModal" class="artistModal" role="dialog" aria-modal="true" aria-label="Fiche artiste">
            <div class="modalContent">
                <div class="artistModalBar">
                    <button type="button" class="closeModal" aria-label="Fermer la fiche artiste">&times;</button>
                </div>
                <div id="artistDetailsContainer"></div>
            </div>
        </div>

    </main>
    <?php
    return ob_get_clean();
}
?>
