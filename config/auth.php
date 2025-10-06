<?php

return [

    'defaults' => [
        // Si tu app principal es API móvil, deja 'api'.
        // Si tu flujo principal es web usuario, puedes poner 'web'.
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        // ==== WEB (SESSION) ====
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],

        // ==== API (JWT) ====
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        // Opcional: si quieres login JWT separado para administradores
        'admin_api' => [
            'driver' => 'jwt',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],

        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrador::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Opcional: broker separado para admins
        // 'admins' => [
        //     'provider' => 'admin',
        //     'table' => 'password_reset_tokens',
        //     'expire' => 60,
        //     'throttle' => 60,
        // ],
    ],

    'password_timeout' => 10800,
];
