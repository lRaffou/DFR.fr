<?php
function dfrEventCalendar(array $events, ?DateTimeImmutable $now = null): array
{
    $now ??= new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    $calendar = ['upcoming' => [], 'past' => []];
    foreach ($events as $event) {
        $calendar[new DateTimeImmutable($event['end']) > $now ? 'upcoming' : 'past'][] = $event;
    }
    usort($calendar['upcoming'], static fn($a, $b) => strtotime($a['start']) <=> strtotime($b['start']));
    usort($calendar['past'], static fn($a, $b) => strtotime($b['start']) <=> strtotime($a['start']));
    return $calendar;
}
