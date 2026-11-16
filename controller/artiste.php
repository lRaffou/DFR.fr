<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewArtiste.php';
require __DIR__ . '/../view/viewFooter.php';

echo renderHeader('Artistes | Dao Family Records', true);
echo renderArtiste();
echo renderFooter();