<?php
// Suppression de session_start() car nous n'avons plus besoin de gérer les sessions

include '../view/viewHeader.php';
include '../view/view404.php';
include '../view/viewFooter.php';

http_response_code(404);
echo renderHeader('Page introuvable | Dao Family Records');
echo render404();
echo renderFooter();
?>
