<?php

use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'connection' => null,
    'table' => 'sessions',
    'lifetime' => env('SESSION_LIFETIME', 120),
    'encrypt' => false,
    'lottery' => [2, 100],
    'expire_on_close' => false,
    'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
];
