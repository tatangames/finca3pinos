<?php

return [

    'log_failures' => true,
    'include_currency' => true,

    // Usa la base local de MaxMind
    'service' => 'maxmind_database',



    'services' => [
        'maxmind_database' => [
            'class'         => \Torann\GeoIP\Services\MaxMindDatabase::class,
            // Usa una carpeta dedicada en storage:
            'database_path' => storage_path('geoip/GeoLite2-City.mmdb'),

            // Necesarios para geoip:update (desde 2024 redirige a Cloudflare R2, Guzzle sigue la redirección):
            'update_url'    => 'https://download.maxmind.com/app/geoip_download',
            'license_key'   => env('MAXMIND_LICENSE_KEY'),
            'edition'       => 'GeoLite2-City',

            // (opcional) idiomas preferidos para nombres de regiones/ciudades
            'locales'       => ['en'],
        ],

        // Si NO usarás el servicio web, puedes dejarlo así o eliminarlo:
        'maxmind_api' => [
            'class'       => \Torann\GeoIP\Services\MaxMindWebService::class,
            'user_id'     => env('MAXMIND_USER_ID'), // solo si compras el web service
            'license_key' => env('MAXMIND_LICENSE_KEY'),
            'locales'     => ['en'],
        ],

        // Otros proveedores HTTP opcionales… (puedes dejarlos como están o quitarlos)
    ],


    // Cache simple (o 'none' si prefieres)
    'cache' => 'all',

    // En file/array NO hay tags → ponlo en null o []
    'cache_tags' => null,

    'cache_expires' => 30,

    // Tu fallback (está bien que sea SV)
    'default_location' => [
        'ip'           => '127.0.0.1',
        'iso_code'     => 'SV',
        'country'      => 'El Salvador',
        'city'         => 'San Salvador',
        'state'        => 'SS',
        'state_name'   => 'San Salvador',
        'postal_code'  => '1101',
        'lat'          => 13.6929,
        'lon'          => -89.2182,
        'timezone'     => 'America/El_Salvador',
        'continent'    => 'NA',
        'default'      => true,
        'currency'     => 'USD',
    ],
];
