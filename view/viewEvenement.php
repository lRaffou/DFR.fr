<?php
function renderEvenement()
{
    ob_start();
    ?>
    <main class="mainEvent">
        <section class="eventHero">
            <h1>Nos Événements</h1>
            <p>Découvrez nos prochains événements et revivez nos moments forts</p>
        </section>

        <section class="featuredEvent">
            <h2>Prochain Événement</h2>
            <div class="eventContainer">
                <article class="eventCard featured">
                    <div class="eventImage">
                        <img src="\img\nextEvent.jpg" alt="Coeur Meurtri - Open Air">
                        <div class="eventDate">
                            <span class="day">10 -11</span>
                            <span class="month">MAI</span>
                        </div>
                    </div>
                    <div class="eventInfo">
                        <h3>🔴 Coeur Meurtri - Open Air 🔴</h3>
                        <div class="eventTags">
                            <span class="tag">Bass House</span>
                            <span class="tag">Techno</span>
                            <span class="tag">Hard Techno</span>
                        </div>
                        <p class="eventDescription">
                            "Cœur Meurtri by DJ Babe" est un événement musical qui réunira exclusivement des artistes
                            féminines, mettant en lumière leur créativité, leur diversité et leur force. Imaginé par Sophie
                            Dao,
                            alias DJ Babe, talent émergent toulousaine, cet événement vous garanti une expérience
                            inoubliable.
                            Ce projet incarne une vision inclusive et bienveillante, célébrant la richesse artistique et
                            culturelle des femmes dans la musique.
                            Le cœur battra plus fort au rythme de COEUR MEURTRI, l'open air 100 % féminin orchestré par DJ
                            Babe ! Pendant deux jours, viens vibrer dans une ambiance électrique et bienveillante, en plein
                            air au Port de l'Embouchure à Toulouse.
                        </p>
                        <p class="eventDetails">
                            Rejoignez-nous pour une fête où règnent les good vibes et la découverte de nouveaux talents.
                            Cette édition promet d'être spectaculaire avec une sélection d'artistes confirmés et
                            émergents.
                        </p>
                        <div class="eventActions">
                            <a href="https://fb.me/e/7OXhf42dF" target="_blank" class="btnEvent">Plus
                                d'infos</a>
                            <a href="https://shotgun.live/fr/events/after-officiel-coeur-meurtri-open-air-2025"
                                target="_blank" class="btnEvent btnPrimary">Réserver</a>
                        </div>
                    </div>
                </article>
                <article class="eventCard featured">
                    <div class="eventImage">
                        <img src="\img\nextEvent2.jpg" alt="The Red Night Off - Édition #5">
                        <div class="eventDate">
                            <span class="day">16</span>
                            <span class="month">MAI</span>
                        </div>
                    </div>
                    <div class="eventInfo">
                        <h3>🔴 The Red Night Off - Édition #5 🔴</h3>
                        <div class="eventTags">
                            <span class="tag">Bass House</span>
                            <span class="tag">Techno</span>
                            <span class="tag">Hard Techno</span>
                        </div>
                        <p class="eventDescription">
                            🚨 Toulouse, préparez-vous pour une nouvelle nuit d’exception ! Le vendredi 16 mai 2025, le Full
                            House accueille la cinquième édition de la Red Night, sous une lune rouge incandescente ! 🌕🔴
                            Plongez dans une ambiance électrisante de 1h à 7h du matin avec un line-up brûlant prêt à faire
                            vibrer les murs du club ! 🔥🎶

                            LINE-UP EXCLUSIF 🎧
                            🔴 NIVK – Intrusion : Une hard techno percutante et des basses sombres... NIVK débarque pour
                            retourner le dancefloor avec un set intense et sans compromis !
                            🔴 RED MARSHAL – DF Records : De retour à domicile, RED MARSHAL promet une performance
                            explosive. Préparez-vous à des pépites sonores made in DF Records !
                            🔴 FERNANDO GOMEZ – DF Records : Il enchaînera groove et puissance avec une signature sonore
                            unique !
                        </p>
                        <p class="eventDetails">
                            📍 Lieu : Full House, 19 Rue Caffarelli, 31000 Toulouse<br>🕐 Horaires : 1H - 7H<br>🎟️ Une nuit
                            rouge passion, une immersion totale dans l’univers de la techno underground…
                            Serez-vous de la partie ?<br>🔗 Réservez vos places dès maintenant et rejoignez la légende de la
                            Red Night !
                        </p>
                        <div class="eventActions">
                            <a href="https://www.instagram.com/p/DIvfIeyICU5/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==" target="_blank" class="btnEvent">Plus
                                d'infos</a>
                            <a href="https://shotgun.live/fr/events/the-red-night-off-edition-5"
                                target="_blank" class="btnEvent btnPrimary">Réserver</a>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="pastEvents">
            <h2>Événements Passés</h2>
            <div class="eventGrid">
                <article class="eventCard">
                    <div class="eventImage">
                        <img src="/img/The_Red_Night_-_Open_Air.jpg" alt="The Red Night - Open Air">
                    </div>
                    <div class="eventInfo">
                        <h3>The Red Night - Open Air</h3>
                        <p>Une soirée mémorable sous les étoiles</p>
                        <a href="https://www.instagram.com/p/C9C2SH5oDPu/" target="_blank" class="btnEvent">Voir les
                            photos</a>
                    </div>
                </article>

                <article class="eventCard">
                    <div class="eventImage">
                        <img src="/img/Sans titre-gsd1.png" alt="Red Rave de Red Marshal">
                    </div>
                    <div class="eventInfo">
                        <h3>Red Rave by Red Marshal</h3>
                        <p>Une nuit de techno intense</p>
                        <a href="https://www.instagram.com/p/Cy2z4sLo6Lh/" target="_blank" class="btnEvent">Voir les
                            photos</a>
                    </div>
                </article>

                <article class="eventCard">
                    <div class="eventImage">
                        <img src="/img/Sans titre-2.png" alt="Red Rave de Red Marshal">
                    </div>
                    <div class="eventInfo">
                        <h3>Red Rave Special Edition</h3>
                        <p>Une édition spéciale inoubliable</p>
                        <a href="https://www.instagram.com/p/CyX3byPoxY5/" target="_blank" class="btnEvent">Voir les
                            photos</a>
                    </div>
                </article>
            </div>
        </section>
    </main>
    <?php
    return ob_get_clean();
}
?>