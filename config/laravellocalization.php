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

    ],

    // ============== NOTAS =================
    // Aqui se agrega el nuevo idioma
    // en App/Helpers/RegionHelper  tambien se agregara el nuevo IDIOMA
    // en Lang/ y sus carpetas
    // en vista contacto






    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL'  => false,

    // No necesitas mapping para esto
    'localesMapping' => [],
];

