<?php

require_once '../view/viewHeader.php';
require_once '../view/viewAlbum.php';
require_once '../view/viewFooter.php';

function renderAlbumPage()
{
    $header = renderHeader('Sorties | Dao Family Records');
    $content = renderAlbum();
    $footer = renderFooter();

    echo $header . $content . $footer;
}

renderAlbumPage();
?>
