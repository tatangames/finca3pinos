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

        // 👇 Nuevo idioma
        'ko' => [
            'name'    => 'Korean',
            'script'  => 'Kore',
            'native'  => '한국어',
            'regional'=> 'ko_KR',
            'url'     => 'kr', // metadato tuyo; no cambia el prefijo /ko
        ],

        'pt' => [
            'name'     => 'Portuguese',
            'script'   => 'Latn',
            'native'   => 'Português',
            'regional' => 'pt_PT', // o 'pt_BR' según el público objetivo
            'url'      => 'pt',
        ],
    ],

    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL'  => false,

    // No necesitas mapping para esto
    'localesMapping' => [],
];

