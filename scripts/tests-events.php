<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../model/event-calendar.php';
require __DIR__ . '/../view/viewEvenement.php';
require __DIR__ . '/../components/next-event.php';
$checks = 0;
function checkEvent(bool $condition, string $message): void
{
    global $checks;
    if (!$condition) { throw new RuntimeException($message); }
    $checks++;
}
$overnight = ['start' => '2026-12-31T18:00:00+01:00', 'end' => '2027-01-01T05:00:00+01:00'];
$later = ['start' => '2027-02-01T18:00:00+01:00', 'end' => '2027-02-02T05:00:00+01:00'];
$during = dfrEventCalendar([$later, $overnight], new DateTimeImmutable('2027-01-01T01:00:00+01:00'));
checkEvent(count($during['upcoming']) === 2 && !$during['past'], 'Une soirée reste active après minuit.');
checkEvent($during['upcoming'][0] === $overnight, 'Les dates à venir sont classées de la plus proche à la plus lointaine.');
$atEnd = dfrEventCalendar([$overnight], new DateTimeImmutable('2027-01-01T04:00:00Z'));
checkEvent(count($atEnd['past']) === 1 && !$atEnd['upcoming'], 'Archivage à la fin, en tenant compte du fuseau.');
$after = dfrEventCalendar([$overnight, $later], new DateTimeImmutable('2027-03-01T00:00:00+01:00'));
checkEvent($after['past'][0] === $later, 'Les archives présentent la plus récente en premier.');
$confirmed = $overnight + [
    'project' => 'The Red Night',
    'title' => 'Édition Nuit & Jour',
    'date' => '31 décembre 2026 – 1er janvier 2027',
    'venue' => 'Full House · Toulouse',
    'url' => 'https://shotgun.live/fr/events/test-dfr',
];
$second = $later + array_replace($confirmed, ['title' => 'Autre date']);
$preview = renderNextDfrEvent([$second, $confirmed], new DateTimeImmutable('2026-11-15T12:00:00+01:00'));
checkEvent(str_contains($preview, 'Édition Nuit &amp; Jour') && !str_contains($preview, 'Autre date')
    && str_contains($preview, 'Full House · Toulouse') && str_contains($preview, 'Voir sur Shotgun'),
    'L’accueil présente uniquement la date confirmée la plus proche avec le lieu et la billetterie.');
$livePreview = renderNextDfrEvent([$confirmed], new DateTimeImmutable('2027-01-01T01:00:00+01:00'));
checkEvent(str_contains($livePreview, 'En ce moment'), 'L’accueil garde la soirée en cours après minuit.');
checkEvent(renderNextDfrEvent([$confirmed], new DateTimeImmutable('2027-01-01T04:00:00Z')) === '',
    'Le rendez-vous disparaît à l’heure de fin.');
checkEvent(renderNextDfrEvent([]) === '', 'L’accueil omet le bloc en l’absence de date confirmée.');
$events = require __DIR__ . '/../model/events.php';
foreach ($events as $event) {
    checkEvent(new DateTimeImmutable($event['end']) > new DateTimeImmutable($event['start']), 'Dates cohérentes : ' . $event['title']);
    checkEvent(file_exists(__DIR__ . '/..' . $event['image']), 'Affiche présente : ' . $event['title']);
    $html = renderEventArchiveCard($event, true);
    checkEvent(!str_contains($html, 'Réserver') && str_contains($html, 'Archive'), 'Aucune réservation sur une archive.');
}
echo "$checks contrôles événements réussis.\n";
