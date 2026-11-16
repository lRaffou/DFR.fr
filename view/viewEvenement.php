<?php
require_once __DIR__ . '/../model/event-calendar.php';
require_once __DIR__ . '/../components/projects.php';

function renderEventPoster(array $event): void
{
    $escape = static fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ?>
    <picture>
        <?php if (isset($event['mobile_thumbnail'])): ?>
            <source media="(max-width: 700px)" srcset="<?= $escape($event['mobile_thumbnail']) ?>">
        <?php endif; ?>
        <img src="<?= $escape($event['thumbnail'] ?? $event['image']) ?>" alt="" loading="lazy" decoding="async">
    </picture>
    <?php
}

function renderEventArchiveCard(array $event, bool $past): string
{
    $escape = static fn($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ob_start(); ?>
    <article class="dfrEventCard" data-project="<?= $escape($event['project']) ?>">
        <a class="dfrEventPoster" href="<?= $escape($event['image']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Voir l’affiche de <?= $escape($event['project'] . ' — ' . $event['title']) ?>">
            <?php renderEventPoster($event); ?>
        </a>
        <div class="dfrEventContent">
            <p class="dfrEyebrow"><?= $escape($event['project']) ?> · <?= $past ? 'Archive' : 'À venir / en cours' ?></p>
            <h3><?= $escape($event['title']) ?></h3>
            <p class="dfrEventDate"><time datetime="<?= $escape($event['start']) ?>"><?= $escape($event['date']) ?></time></p>
            <p class="dfrEventVenue"><?= $escape($event['venue']) ?></p>
            <div class="dfrEventLinks">
                <a href="<?= $escape($event['url']) ?>" target="_blank" rel="noopener noreferrer"><?= $past ? 'Infos de l’édition' : 'Voir les informations' ?> <span aria-hidden="true">↗</span></a>
            </div>
        </div>
        <details class="dfrEventDetails"<?= $past ? '' : ' open' ?>>
            <summary><?= empty($event['media']) ? 'Programme' : 'Programme et retours' ?></summary>
            <div class="dfrEventDetailsBody">
                <p><?= $escape($event['description']) ?></p>
                <p class="dfrEventLineup"><strong>À l’affiche</strong> · <?= $escape($event['lineup']) ?></p>
                <?php if (!empty($event['media'])): ?>
                    <div class="dfrEventLinks">
                        <?php foreach ($event['media'] as $label => $url): ?>
                            <a href="<?= $escape($url) ?>" target="_blank" rel="noopener noreferrer"><?= $escape($label) ?> <span aria-hidden="true">↗</span></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </details>
    </article>
    <?php return ob_get_clean();
}

function renderEvenement()
{
    $calendar = dfrEventCalendar(require __DIR__ . '/../model/events.php');
    $projects = require __DIR__ . '/../model/projects.php';
    $shotgunProjects = array_filter($projects, static fn(array $project) => isset($project['links']['Shotgun']));
    $latestArchives = [];
    foreach ($calendar['past'] as $event) {
        $latestArchives[$event['project']] ??= $event;
    }
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ob_start(); ?>
    <main class="mainEvent editorialPage">
        <header class="editorialHeader">
            <h1>Événements</h1>
            <p>The Red Night et Cœur Meurtri, les rendez-vous de Dao Family Records à Toulouse.</p>
            <nav class="dfrEventJump" aria-label="Sur cette page">
                <a href="#next-events-title">Prochaines dates</a>
                <a href="#past-events-title">Archives</a>
            </nav>
        </header>
        <section class="dfrEventSection" aria-labelledby="next-events-title">
            <div class="editorialSectionHeading">
                <h2 id="next-events-title">Prochaines dates</h2>
            </div>
            <?php if ($calendar['upcoming']): ?>
                <div class="dfrEventGrid">
                    <?php foreach ($calendar['upcoming'] as $event) { echo renderEventArchiveCard($event, false); } ?>
                </div>
            <?php endif; ?>
            <div class="dfrEventGrid dfrShotgunGrid">
                <?php foreach ($shotgunProjects as $id => $project): ?>
                    <?php
                    $shotgun = $project['links']['Shotgun'];
                    $links = ['Ouvrir sur Shotgun' => $shotgun] + array_diff_key($project['links'], ['Shotgun' => true]);
                    $archive = $latestArchives[$project['name']] ?? null;
                    ?>
                    <article class="dfrProject dfrProject--<?= $escape($id) ?> dfrEventConcept" id="<?= $escape($id) ?>" aria-labelledby="<?= $escape($id) ?>-title">
                        <div class="dfrEventConceptIntro">
                            <div class="dfrEventConceptHeading">
                                <div>
                                    <p class="dfrEyebrow"><?= $escape($project['eyebrow']) ?></p>
                                    <h3 id="<?= $escape($id) ?>-title"><?= $escape($project['name']) ?></h3>
                                    <p class="dfrProjectDescription"><?= $escape($project['description']) ?></p>
                                </div>
                                <?php if ($archive !== null): ?>
                                    <a class="dfrEventConceptPoster" href="<?= $escape($archive['image']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Voir l’affiche d’archive de <?= $escape($project['name'] . ' — ' . $archive['title']) ?>">
                                        <?php renderEventPoster($archive); ?>
                                        <span>Affiche d’archive</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php renderProjectLinks($links, $project['name']); ?>
                        </div>
                        <div class="dfrShotgunAgenda">
                            <iframe src="<?= $escape($shotgun . '?embedded=1&ui=dark') ?>"
                                title="Agenda Shotgun de <?= $escape($project['name']) ?>" loading="lazy"
                                sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation"></iframe>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="dfrEventSection" aria-labelledby="past-events-title">
            <div class="editorialSectionHeading">
                <h2 id="past-events-title">Archives</h2>
            </div>
            <div class="dfrEventGrid dfrEventArchiveGrid">
                <?php foreach ($calendar['past'] as $event) { echo renderEventArchiveCard($event, true); } ?>
            </div>
        </section>
    </main>
    <?php return ob_get_clean();
}
