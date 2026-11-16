<?php
require __DIR__ . '/components/next-event.php';
require __DIR__ . '/view/viewHeader.php';
require __DIR__ . '/view/viewAcceuil.php';
require __DIR__ . '/view/viewFooter.php';

$nextEvent = renderNextDfrEvent(require __DIR__ . '/model/events.php');
$stylesheets = ['home.css', 'artist-profile.css'];
if ($nextEvent !== '') {
    $stylesheets[] = 'next-event.css';
}
echo renderHeader(includeArtistScript: true, metadata: ['canonical' => dfrAbsoluteUrl('/')], stylesheets: $stylesheets);
echo renderAcceuil($nextEvent);
echo renderFooter();
