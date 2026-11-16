<?php
require_once __DIR__ . '/../model/event-calendar.php';

function renderNextDfrEvent(array $events, ?DateTimeImmutable $now = null): string
{
    $now ??= new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    $event = dfrEventCalendar($events, $now)['upcoming'][0] ?? null;
    if ($event === null) {
        return '';
    }

    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $inProgress = new DateTimeImmutable($event['start']) <= $now;
    $isShotgun = parse_url($event['url'], PHP_URL_HOST) === 'shotgun.live';
    ob_start();
    ?>
    <section class="dfrNextEvent" aria-labelledby="dfr-next-event-title">
        <div>
            <p class="dfrEyebrow"><?= $inProgress ? 'En ce moment' : 'Prochain rendez-vous' ?> · <?= $escape($event['project']) ?></p>
            <h2 id="dfr-next-event-title"><?= $escape($event['title']) ?></h2>
            <p class="dfrNextEventDetails">
                <time datetime="<?= $escape($event['start']) ?>"><?= $escape($event['date']) ?></time>
                <span><?= $escape($event['venue']) ?></span>
            </p>
        </div>
        <a class="dfrNextEventAction" href="<?= $escape($event['url']) ?>" target="_blank" rel="noopener noreferrer">
            <?= $isShotgun ? 'Voir sur Shotgun' : 'Voir l’annonce' ?> <span aria-hidden="true">↗</span>
        </a>
    </section>
    <?php
    return ob_get_clean();
}
