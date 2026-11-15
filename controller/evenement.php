<?php
// Suppression de session_start() car nous n'avons plus besoin de gérer les sessions

include '../view/viewHeader.php';
include '../view/viewEvenement.php';
include '../view/viewFooter.php';

echo renderHeader('Événements | Dao Family Records');
echo renderEvenement();
echo renderFooter();
?>
