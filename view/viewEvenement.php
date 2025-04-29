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
                        <img src="/img/nextEvent.jpg" alt="Coeur Meurtri - Open Air">
                        <div class="eventDate">
                            <span class="day">10-11</span>
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
                            "Cœur Meurtri by DJ Babe" est un événement musical 100% féminin au Port de l'Embouchure à
                            Toulouse.
                        </p>
                        <p class="eventDetails">
                            Deux jours de performances exclusives pour célébrer les talents féminins dans l'électronique !
                        </p>
                        <div class="eventActions">
                            <a href="https://fb.me/e/7OXhf42dF" target="_blank" class="btnEvent">Plus d'infos</a>
                            <a href="https://shotgun.live/fr/events/after-officiel-coeur-meurtri-open-air-2025"
                                target="_blank" class="btnEvent btnPrimary">Réserver</a>
                        </div>
                    </div>
                </article>

                <article class="eventCard featured">
                    <div class="eventImage">
                        <img src="/img/nextEvent2.jpg" alt="The Red Night Off - Édition #5">
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
                            Retrouvons-nous au Full House pour la 5e édition de la Red Night avec NIVK, Red Marshal et
                            Fernando Gomez !
                        </p>
                        <p class="eventDetails">
                            Nuit rouge passion de 1H à 7H au Full House Toulouse.
                        </p>
                        <div class="eventActions">
                            <a href="https://www.instagram.com/p/DIvfIeyICU5/" target="_blank" class="btnEvent">Plus
                                d'infos</a>
                            <a href="https://shotgun.live/fr/events/the-red-night-off-edition-5" target="_blank"
                                class="btnEvent btnPrimary">Réserver</a>
                        </div>
                    </div>
                </article>

            </div>
        </section>

        <section class="pastEvents">
            <h2>Événements Passés</h2>
            <div class="pastEventGrid">

                <article class="pastEventCard">
                    <div class="pastEventImage">
                        <img src="/img/Red_Night.png" alt="The Red Night - Open Air">
                    </div>
                    <div class="pastEventInfo">
                        <h3>The Red Night - Open Air</h3>
                        <p>Une soirée mémorable sous les étoiles</p>
                        <div class="pastEventAction">
                            <a href="https://www.instagram.com/p/C9C2SH5oDPu/" target="_blank" class="pastEventButton">Voir
                                les photos</a>
                        </div>
                    </div>
                </article>

                <article class="pastEventCard">
                    <div class="pastEventImage">
                        <img src="/img/Red_Night.png" alt="Red Rave by Red Marshal">
                    </div>
                    <div class="pastEventInfo">
                        <h3>Red Rave by Red Marshal</h3>
                        <p>Une nuit de techno intense</p>
                        <div class="pastEventAction">
                            <a href="https://www.instagram.com/p/Cy2z4sLo6Lh/" target="_blank" class="pastEventButton">Voir
                                les photos</a>
                        </div>
                    </div>
                </article>

                <article class="pastEventCard">
                    <div class="pastEventImage">
                        <img src="/img/Red_Night.png" alt="Red Rave Special Edition">
                    </div>
                    <div class="pastEventInfo">
                        <h3>Red Rave Special Edition</h3>
                        <p>Une édition spéciale inoubliable</p>
                        <div class="pastEventAction">
                            <a href="https://www.instagram.com/p/CyX3byPoxY5/" target="_blank" class="pastEventButton">Voir
                                les photos</a>
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