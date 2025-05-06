<?php
function renderVideo()
{
    ob_start();
    ?>
    <main class="mainVideo">

        <section class="videoHero">
            <h1>Nos Vidéos</h1>
            <p>Découvrez nos créations musicales les plus récentes</p>
        </section>

        <section class="featuredVideo">
            <h2>Vidéo à la Une</h2>
            <div class="videoContainer">
                <article class="video">
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/videoseries?si=9n7Tnr8qqOqHir4m&amp;list=PLfGk9MSaVom5-wEtYZ2lM2a99SeHHF6sg"
                        title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                </article>
                <div class="videoInfo">
                    <h3>Dao Family Records - Playlists</h3>
                    <p>Des créations réalisées par nos artistes</p>
                </div>
            </div>
        </section>

        <section class="videoGallery">
            <h2>Notre Collection</h2>
            <div class="videoGrid">

                <?php
                $videos = [
                    ["id" => "eoLwaREoCls", "title" => "Red Marshal - Un Violeurs Sur Ton Chemin"],
                    ["id" => "CJlThSkDbvA", "title" => "Red Marshal - Above"],
                    ["id" => "xepOSI0yiTk", "title" => "Red Marshal - Coucou les Congolais - Squid Game 2 (Hard Techno Remix) Mingle Game Song <br> 밍글 게임 노래"],
                    ["id" => "1bou4SORFY4", "title" => "Red Marshal - Acid Japan"],
                    ["id" => "EFj2f6M1IYA", "title" => "Red Marshal - Brother"],
                    ["id" => "TvbCJdJN5R4", "title" => "Red Marshal - Follow Me"],
                    ["id" => "MkWbtJhjOVU", "title" => "Red Marshal - The Darkest Night"],
                    ["id" => "H4wUMWz1wyg", "title" => "Red Marshal - Warning"],
                    ["id" => "iOs7LpGDoew", "title" => "Red Marshal - Tokyo Techno"],
                    ["id" => "GdM9Vg8rWgs", "title" => "Red Marshal - Backstreet"],
                    ["id" => "vD7fkc8rpRw", "title" => "Red Marshal - Happiness"],
                    ["id" => "9M9b8pPfQxw", "title" => "Red Marshal - Seven Dark"],
                    ["id" => "9_wwx_cSg8w", "title" => "Red Marshal - Earth"],
                    ["id" => "kBEVORZtE7g", "title" => "Red Marshal - Lost"],
                    ["id" => "aR9arBaYTTU", "title" => "Red Marshal - The Red Night"],
                    ["id" => "AAEuRLXiaZE", "title" => "Red Marshal - Port de l'embourchure , Toulouse - Official Aftermovie"],
                    ["id" => "mRf-xqitwe8", "title" => "Red Marshal - Live on “Pont de Bourret”"],
                    ["id" => "dSO9va70v0c", "title" => "Red Marshal - Dark Power"],
                    ["id" => "QL5o1O7Isb0", "title" => "Red Marshal - Wanted"],
                    ["id" => "j4EYV1JAKko", "title" => "Red Marshal - A Cry In The Darkness"],
                    ["id" => "rnPkKmCDbos", "title" => "Red Marshal - My Eyes"],
                    ["id" => "KiutocisUOs", "title" => "Red Marshal - Vibes"],
                    ["id" => "OAQrUWTs_cA", "title" => "Red Marshal - Pleasure"],
                    ["id" => "l4cAX6Nh5T4", "title" => "Dao Brothers - Burning"],
                    ["id" => "X3FYFQ_Ywd0", "title" => "Dao Brothers - Into The Club"],
                    ["id" => "7eiQKn3ReNw", "title" => "Dao Brothers - Dancing In The House"],
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
                        <iframe id="modalVideo" width="560" height="315" src="" title="YouTube video player" frameborder="0"
                            allowfullscreen></iframe>
                    </article>
                </div>
            </div>
        </div>

    </main>
    <?php
    return ob_get_clean();
}
?>