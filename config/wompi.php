<?php
return [
    'env' => env('WOMPI_ENV','sv'),
    'sv'  => [
        'auth' => env('WOMPI_SV_AUTH','https://id.wompi.sv/connect/token'),
        'api'  => env('WOMPI_SV_API','https://api.wompi.sv'),
    ],
    'app_id'   => env('WOMPI_APP_ID'),
    'secret'   => env('WOMPI_SECRET'),
    'currency' => env('WOMPI_CURRENCY','USD'),
    'return'   => env('WOMPI_RETURN_URL'),
    'notify'   => env('WOMPI_NOTIFY_URL'),
];
