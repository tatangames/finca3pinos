<?php

return [

    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_US',
        ],
        'es' => [
            'name' => 'Español (Latinoamérica)',
            'script' => 'Latn',
            'native' => 'español',
            'regional' => 'es_ES',
        ],
        'sv' => [
            'name' => 'Español (El Salvador)',
            'script' => 'Latn',
            'native' => 'español (SV)',
            'regional' => 'es_SV',
        ],
    ],

    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL' => false,

    // Slugs personalizados → locales reales
    'localesMapping' => [
        'us'       => 'en',
        'latin-es' => 'es',
        'sv'       => 'sv',
    ],

    'urlsIgnored' => ['/skipped'],
    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
