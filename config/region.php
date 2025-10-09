<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Regiones soportadas en la URL
    |--------------------------------------------------------------------------
    |
    | Estos slugs aparecerán como el primer segmento de la URL, por ejemplo:
    |   /us     → inglés
    |   /sv     → español (El Salvador)
    |   /latin-es → español general para Latinoamérica
    |
    */

    'supported' => ['us', 'latin-es', 'sv'],

    /*
    |--------------------------------------------------------------------------
    | Mapeo de región → locale de Laravel
    |--------------------------------------------------------------------------
    |
    | Define qué carpeta de /lang se usará para cada región.
    | Ejemplo: 'us' usa /lang/en/, 'sv' usa /lang/sv/
    |
    */

    'locale_map' => [
        'us'       => 'en',
        'latin-es' => 'latin-es',
        'sv'       => 'sv', // El Salvador → español local
    ],

    /*
    |--------------------------------------------------------------------------
    | Región por defecto
    |--------------------------------------------------------------------------
    |
    | Si no se detecta una región válida en la URL o por IP, se usará esta.
    |
    */

    'default' => 'sv',

    /*
    |--------------------------------------------------------------------------
    | Detección por país ISO2 → región
    |--------------------------------------------------------------------------
    |
    | Mapea el código de país (obtenido por geoip u otro método)
    | a una región del sitio.
    |
    */

    'country_to_region' => [
        'SV' => 'sv', // El Salvador
        'US' => 'us', // Estados Unidos
        'CA' => 'us', // Canadá → inglés
        // el resto caerá en 'latin-es'
    ],
];
