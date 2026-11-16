<?php
const DFR_SPOTIFY_PLAYLIST = '4z6XsMTJ8rsbSGyi20bz9T';

function dfrSite(): array
{
    static $site = [
        'navigation' => [
            ['/index.php', 'Accueil', 'fa-home'],
            ['/controller/evenement.php', 'Événements', 'fa-calendar-alt'],
            ['/controller/artiste.php', 'Artistes', 'fa-music'],
            ['/controller/video.php', 'Vidéos', 'fa-video'],
            ['/controller/album.php', 'Sorties', 'fa-compact-disc'],
        ],
        'contact' => 'mailto:Daofamilyrecords@gmail.com',
        'social' => [
            'YouTube' => [
                'url' => 'https://www.youtube.com/@DaoFamilyRecords',
                'icon' => 'fa-youtube',
                'label' => 'Dao Family Records sur YouTube',
            ],
            'Spotify' => [
                'url' => 'https://open.spotify.com/playlist/' . DFR_SPOTIFY_PLAYLIST,
                'icon' => 'fa-spotify',
                'label' => 'Playlist Dao Family Records sur Spotify',
            ],
            'Instagram' => [
                'url' => 'https://www.instagram.com/dao_family_records/',
                'icon' => 'fa-instagram',
                'label' => 'Dao Family Records sur Instagram',
            ],
            'Facebook' => [
                'url' => 'https://www.facebook.com/redmarshalofficial',
                'icon' => 'fa-facebook',
            ],
        ],
    ];
    return $site;
}
