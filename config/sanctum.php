<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 
        'localhost,127.0.0.1,127.0.0.1:8000,::1,localhost:3000,127.0.0.1:3000'
    )),

    'expiration' => null,

    'middleware' => [
        'verify_csrf_token' => \App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
    ],
];