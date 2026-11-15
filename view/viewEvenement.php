<?php
require_once __DIR__ . '/../model/event-calendar.php';
require_once __DIR__ . '/../components/projects.php';

function renderEventArchiveCard(array $event, bool $past): string
{
    $escape = static fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ob_start(); ?>
    <article class="dfrEventCard">
        <a class="dfrEventPoster" href="<?= $escape($event['image']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Voir l’affiche : <?= $escape($event['project'] . ' — ' . $event['title']) ?>">
            <img src="<?= $escape($event['image']) ?>" alt="Affiche <?= $escape($event['project'] . ' — ' . $event['title']) ?>" loading="lazy">
        </a>
        <div class="dfrEventContent">
            <p class="dfrEyebrow"><?= $escape($event['project']) ?> · <?= $past ? 'Archive' : 'À venir / en cours' ?></p>
            <h3><?= $escape($event['title']) ?></h3>
            <p class="dfrEventDate"><time datetime="<?= $escape($event['start']) ?>"><?= $escape($event['date']) ?></time></p>
            <p class="dfrEventVenue"><?= $escape($event['venue']) ?></p>
            <p><?= $escape($event['description']) ?></p>
            <p class="dfrEventLineup"><strong>À l’affiche :</strong> <?= $escape($event['lineup']) ?></p>
            <div class="dfrEventLinks">
                <a href="<?= $escape($event['url']) ?>" target="_blank" rel="noopener noreferrer"><?= $past ? 'Infos de l’édition' : 'Voir les informations' ?> <span aria-hidden="true">↗</span></a>
                <?php foreach ($event['media'] ?? [] as $label => $url): ?>
                    <a href="<?= $escape($url) ?>" target="_blank" rel="noopener noreferrer"><?= $escape($label) ?> <span aria-hidden="true">↗</span></a>
                <?php endforeach; ?>
            </div>
        </div>
    </article>
    <?php return ob_get_clean();
}

function renderEvenement()
{
    $calendar = dfrEventCalendar(require __DIR__ . '/../model/events.php');
    $projects = require __DIR__ . '/../model/projects.php';
    $shotgunProjects = [$projects['red-night'], $projects['coeur-meurtri']];
    ob_start(); ?>
    <main class="mainEvent">
        <section class="eventHero">
            <h1>Nos Événements</h1>
            <p>The Red Night et Cœur Meurtri : les rendez-vous de la famille DFR à Toulouse.</p>
            <div class="dfrEventJump">
                <a href="#next-events-title">Prochaines dates</a>
                <a href="#past-events-title">Archives</a>
            </div>
        </section>
        <section class="dfrEventSection" aria-labelledby="next-events-title">
            <h2 id="next-events-title">Prochains événements</h2>
            <?php if ($calendar['upcoming']): ?>
                <div class="dfrEventGrid">
                    <?php foreach ($calendar['upcoming'] as $event) { echo renderEventArchiveCard($event, false); } ?>
                </div>
            <?php endif; ?>
            <div class="dfrEventGrid dfrShotgunGrid">
                <?php foreach ($shotgunProjects as $project): ?>
                    <?php $shotgun = $project['links']['Shotgun']; ?>
                    <article class="dfrShotgunAgenda">
                        <div class="dfrShotgunHeading">
                            <h3><?= htmlspecialchars($project['name']) ?></h3>
                            <a href="<?= htmlspecialchars($shotgun) ?>" target="_blank" rel="noopener noreferrer"
                                aria-label="Ouvrir l’agenda <?= htmlspecialchars($project['name']) ?> sur Shotgun">Ouvrir sur Shotgun <span aria-hidden="true">↗</span></a>
                        </div>
                        <iframe src="<?= htmlspecialchars($shotgun . '?embedded=1&ui=dark') ?>"
                            title="Agenda Shotgun de <?= htmlspecialchars($project['name']) ?>" loading="lazy"
                            sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation"></iframe>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?= renderProjects(true) ?>
        <section class="dfrEventSection" aria-labelledby="past-events-title">
            <div class="dfrSectionHeading">
                <p class="dfrEyebrow">Les éditions précédentes</p>
                <h2 id="past-events-title">Événements passés</h2>
                <p>Les affiches, programmes et retours en images de The Red Night et Cœur Meurtri.</p>
            </div>
            <div class="dfrEventGrid">
                <?php foreach ($calendar['past'] as $event) { echo renderEventArchiveCard($event, true); } ?>
            </div>
        </section>
    </main>
    <?php return ob_get_clean();
}
