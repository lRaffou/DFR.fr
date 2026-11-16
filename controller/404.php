<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/view404.php';
require __DIR__ . '/../view/viewFooter.php';

http_response_code(404);
echo renderHeader('Page introuvable | Dao Family Records');
echo render404();
echo renderFooter();