<?php

return [
    // slugs visibles en la URL
    'supported' => ['us', 'latin-es', 'sv'],

    // mapeo a locale de Laravel (traducciones)
    'locale_map' => [
        'us'       => 'en',
        'latin-es' => 'es',
        'sv'       => 'es', // El Salvador en español
    ],

    // región por defecto si no se detecta nada
    'default' => 'sv',

    // Detección por país ISO2 -> región
    'country_to_region' => [
        'SV' => 'sv',
        'US' => 'us',
        'CA' => 'us',
        // el resto caerá a latin-es
    ],
];
