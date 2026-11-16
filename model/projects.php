<?php
require_once __DIR__ . '/site.php';

$social = dfrSite()['social'];
return [
    'dfr' => [
        'name' => 'Dao Family Records', 'eyebrow' => 'Le label · Toulouse',
        'description' => 'Des artistes, des productions et des rendez-vous pour faire vivre la musique électronique, de la house à la hard techno.',
        'links' => [
            'Instagram' => $social['Instagram']['url'],
            'YouTube' => $social['YouTube']['url'],
            'Nos artistes' => '/controller/artiste.php',
        ],
    ],
    'red-night' => [
        'name' => 'The Red Night', 'eyebrow' => 'Les nuits rouges de DFR',
        'description' => 'Au Full House puis au Babylone, les nuits Off font place à la techno et à la hard techno. The Red Night explore aussi la house et la bass house en plein air et mêle DJ sets et cuisine lors des éditions Sushi/Techno.',
        'links' => [
            'Instagram' => 'https://www.instagram.com/the.red.night/',
            'Shotgun' => 'https://shotgun.live/fr/venues/the-red-night',
        ],
    ],
    'coeur-meurtri' => [
        'name' => 'Cœur Meurtri', 'eyebrow' => 'By DJ Babe',
        'description' => 'DJ Babe lance Cœur Meurtri au Full House en décembre 2024. De la techno en club aux open airs du Ramier et aux soirées à la Couleur de la Culotte, le collectif toulousain programme des femmes aux platines.',
        'links' => [
            'Instagram' => 'https://www.instagram.com/coeur_meurtri_by_babe/',
            'Shotgun' => 'https://shotgun.live/fr/venues/c-oe-ur-meurtri-by-dj-babe',
            'TikTok' => 'https://www.tiktok.com/@coeur.meurtri8',
        ],
    ],
];
