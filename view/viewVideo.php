<?php
function renderVideo()
{
    ob_start();
?>
<main class="mainVideo">

<section class="videoHero">
    <h1>Nos Vidéos</h1>
    <p>Découvrez nos dernières créations musicales et performances live</p>
</section>

<section class="featuredVideo">
    <h2>Vidéo à la Une</h2>
    <div class="videoContainer">
        <article class="video">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/EFj2f6M1IYA?si=fkwpugxswrhElOw6" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
        </article>
        <div class="videoInfo">
            <h3>Red Marshal - Into The Club</h3>
            <p>Une performance live capturée lors de notre dernier événement</p>
        </div>
    </div>
</section>

<section class="videoGallery">
    <h2>Notre Collection</h2>
    <div class="videoGrid">

        <?php
        $videos = [
            ["id" => "TvbCJdJN5R4", "title" => "Red Marshal - Follow Me"],
            ["id" => "MkWbtJhjOVU", "title" => "Red Marshal - The Darkest Night"],
            ["id" => "H4wUMWz1wyg", "title" => "Red Marshal - Warning"],
            ["id" => "iOs7LpGDoew", "title" => "Red Marshal - Tokyo Techno"],
            ["id" => "GdM9Vg8rWgs", "title" => "Red Marshal - Backstreet"],
            ["id" => "vD7fkc8rpRw", "title" => "Red Marshal - Happiness"],
            ["id" => "9M9b8pPfQxw", "title" => "Red Marshal - Seven Dark"],
            ["id" => "9_wwx_cSg8w", "title" => "Red Marshal - Earth"],
            ["id" => "kBEVORZtE7g", "title" => "Red Marshal - Lost"],
        ];

        foreach ($videos as $video) {
            echo '<article class="videoCard" data-video="' . $video['id'] . '">
                    <div class="videoThumbnail">
                        <img src="https://img.youtube.com/vi/' . $video['id'] . '/maxresdefault.jpg" alt="' . $video['title'] . '">
                        <div class="playButton">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="videoDetails">
                        <h3>' . $video['title'] . '</h3>
                        <p>Clip officiel</p>
                    </div>
                  </article>';
        }
        ?>

    </div>
</section>

<div id="videoModal" class="modal">
    <div class="modalContent">
        <span class="closeModal">&times;</span>
        <div class="videoContainer">
            <article class="video">
                <iframe id="modalVideo" width="560" height="315" src="" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
            </article>
        </div>
    </div>
</div>

</main>
<?php
    return ob_get_clean();
}
?>