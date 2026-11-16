<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewAlbum.php';
require __DIR__ . '/../view/viewFooter.php';

echo renderHeader('Sorties | Dao Family Records');
echo renderAlbum();
echo renderFooter();