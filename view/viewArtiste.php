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

    <article class="artistCard" data-artist="red-marshal">
        <div class="artistImage">
            <img src="/img/artistes/artist1.png" alt="Red Marshal">
            <div class="artistOverlay">
                <div class="artistInfo">
                    <h2>Red Marshal</h2>
                    <p>DJ - Producteur</p>
                    <span class="artistGenre">Hard Techno</span>
                </div>
            </div>
        </div>
    </article>

    <article class="artistCard" data-artist="dj-babe">
        <div class="artistImage">
            <img src="/img/artistes/artist2.png" alt="Dj Babe">
            <div class="artistOverlay">
                <div class="artistInfo">
                    <h2>Dj Babe</h2>
                    <p>DJ - Producteur</p>
                    <span class="artistGenre">Tech House</span>
                </div>
            </div>
        </div>
    </article>

    <article class="artistCard" data-artist="fernando">
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

    <article class="artistCard" data-artist="dao-brothers">
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

</section>

<!-- Modal pour afficher les détails de l'artiste -->
<div id="artistModal" class="artistModal">
    <div class="modalContent">
        <span class="closeModal">&times;</span>
        <div id="artistDetailsContainer"></div>
    </div>
</div>

</main>
<?php
    return ob_get_clean();
}
?>
