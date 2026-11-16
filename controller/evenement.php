<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewEvenement.php';
require __DIR__ . '/../view/viewFooter.php';

echo renderHeader('Événements | Dao Family Records', stylesheets: ['event-concepts.css']);
echo renderEvenement();
echo renderFooter();
