<?php

return [
    'log_failures' => true,
    'include_currency' => true,

    'service' => 'maxmind_database',

    'services' => [
        'maxmind_database' => [
            'class'         => \Torann\GeoIP\Services\MaxMindDatabase::class,
            // 👇 clave correcta para este servicio
            'database_path' => storage_path('geoip/GeoLite2-City.mmdb'),
            'locales'       => ['en'],
        ],
    ],

    'cache' => 'all',
    'cache_tags' => null,
    'cache_expires' => 30,

    'default_location' => [
        'ip'           => '127.0.0.1',
        'iso_code'     => 'SV',
        'country'      => 'El Salvador',
        'city'         => 'San Salvador',
        'lat'          => 13.6929,
        'lon'          => -89.2182,
        'timezone'     => 'America/El_Salvador',
    ],
];
