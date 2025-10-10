<?php

return [
    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_US',
            'url' => 'us',          // ← SEGMENTO /us
        ],
        'es' => [
            'name' => 'Español (LatAm)',
            'script' => 'Latn',
            'native' => 'español',
            'regional' => 'es_ES',
            'url' => 'latin-es',    // ← SEGMENTO /latin-es
        ],
        'sv' => [
            'name' => 'Español (El Salvador)',
            'script' => 'Latn',
            'native' => 'español (SV)',
            'regional' => 'es_SV',
            'url' => 'sv',          // ← SEGMENTO /sv
        ],
    ],

    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL'  => false,

    // No necesitas mapping para esto
    'localesMapping' => [],
];

