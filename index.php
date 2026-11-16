<?php
require __DIR__ . '/components/next-event.php';
require __DIR__ . '/view/viewHeader.php';
require __DIR__ . '/view/viewAcceuil.php';
require __DIR__ . '/view/viewFooter.php';

$nextEvent = renderNextDfrEvent(require __DIR__ . '/model/events.php');
echo renderHeader(stylesheets: $nextEvent === '' ? ['home.css'] : ['home.css', 'next-event.css']);
echo renderAcceuil($nextEvent);
echo renderFooter();
