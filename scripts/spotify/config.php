<?php
// Public settings only. OAuth tokens MUST live outside the website and Git checkout.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
return [
    'playlist_id' => '4z6XsMTJ8rsbSGyi20bz9T',
    'owner_id' => '11182467653',
    'market' => 'FR',
    'artists' => [
        '3zpewQ11GYG2ev9Ngdsvi9' => 'Red Marshal',
        '5PyJWzxbDt81teQi2ZzL8n' => 'DJ Babe',
        '51UZk9XPK15uZgwQEWE3MK' => 'Fernando Gomez',
        '4FP9ILC4hO3YvOb2R0NIzj' => 'Dao Brothers',
        '6WQRoPuaY9eGi72nNcSXhu' => 'Zoé Kunter',
        '4NqtEeAINDNe0G3FiHbqbs' => 'Frenzysax',
    ],
    // Exclusions prevent future additions; they never remove existing playlist items.
    'excluded_track_ids' => [],
    'excluded_album_ids' => [],
    'excluded_isrcs' => [],
    'cache_seconds' => 7 * 86400,
];
