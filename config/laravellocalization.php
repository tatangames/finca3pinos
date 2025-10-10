<?php

return [

    'supportedLocales' => [
        'en' => ['name'=>'English','script'=>'Latn','native'=>'English','regional'=>'en_US'],
        'es' => ['name'=>'Español (LatAm)','script'=>'Latn','native'=>'español','regional'=>'es_ES'],
        'sv' => ['name'=>'Español (El Salvador)','script'=>'Latn','native'=>'español (SV)','regional'=>'es_SV'],
    ],
    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL' => false,

// Para aceptar tus slugs entrantes
    'localesMapping' => [
        'us'       => 'en',
        'latin-es' => 'es',
        'sv'       => 'sv',
    ],


    'urlsIgnored' => ['/skipped'],
    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
