<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewVideo.php';
require __DIR__ . '/../view/viewFooter.php';

echo renderHeader('Vidéos | Dao Family Records', stylesheets: ['video-editorial.css']);
echo renderVideo();
echo renderFooter();
