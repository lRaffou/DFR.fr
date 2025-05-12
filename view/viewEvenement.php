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
                <?php
                $featuredEvents = [
                    // [
                    //     'image' => '/img/nextEvent.jpg',
                    //     'alt' => 'Coeur Meurtri - Open Air',
                    //     'day' => '10-11',
                    //     'month' => 'MAI',
                    //     'title' => '🔴 Coeur Meurtri - Open Air 🔴',
                    //     'tags' => ['Bass House', 'Techno', 'Hard Techno'],
                    //     'description' => '"Cœur Meurtri by DJ Babe" est un événement musical 100% féminin au Port de l\'Embouchure à Toulouse.',
                    //     'details' => 'Deux jours de performances exclusives pour célébrer les talents féminins dans l\'électronique !',
                    //     'links' => [
                    //         ['url' => 'https://fb.me/e/7OXhf42dF', 'text' => 'Plus d\'infos', 'class' => 'btnEvent'],
                    //         ['url' => 'https://shotgun.live/fr/events/after-officiel-coeur-meurtri-open-air-2025', 'text' => 'Réserver', 'class' => 'btnEvent btnPrimary']
                    //     ]
                    // ],
                    [
                        'image' => '/img/nextEvent2.jpg',
                        'alt' => 'The Red Night Off - Édition #5',
                        'day' => '16',
                        'month' => 'MAI',
                        'title' => '🔴 The Red Night Off - Édition #5 🔴',
                        'tags' => ['Bass House', 'Techno', 'Hard Techno'],
                        'description' => 'Retrouvons-nous au Full House pour la 5e édition de la Red Night avec NIVK, Red Marshal et Fernando Gomez !',
                        'details' => 'Nuit rouge passion de 1H à 7H au Full House Toulouse.',
                        'links' => [
                            ['url' => 'https://www.instagram.com/p/DIvfIeyICU5/', 'text' => 'Plus d\'infos', 'class' => 'btnEvent'],
                            ['url' => 'https://shotgun.live/fr/events/the-red-night-off-edition-5', 'text' => 'Réserver', 'class' => 'btnEvent btnPrimary']
                        ]
                    ]
                ];

                foreach ($featuredEvents as $event): ?>
                    <article class="eventCard featured">
                        <div class="eventImage">
                            <img src="<?= $event['image'] ?>" alt="<?= $event['alt'] ?>">
                            <div class="eventDate">
                                <span class="day"><?= $event['day'] ?></span>
                                <span class="month"><?= $event['month'] ?></span>
                            </div>
                        </div>
                        <div class="eventInfo">
                            <h3><?= $event['title'] ?></h3>
                            <div class="eventTags">
                                <?php foreach ($event['tags'] as $tag): ?>
                                    <span class="tag"><?= $tag ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="eventDescription"><?= $event['description'] ?></p>
                            <p class="eventDetails"><?= $event['details'] ?></p>
                            <div class="eventActions">
                                <?php foreach ($event['links'] as $link): ?>
                                    <a href="<?= $link['url'] ?>" target="_blank"
                                        class="<?= $link['class'] ?>"><?= $link['text'] ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    </main>
    <?php
    return ob_get_clean();
}
?>