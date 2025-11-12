<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\SeoServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Vercel (and similar) mount the project read-only, so fall back to /tmp automatically.
$storagePath = env('APP_STORAGE');

if (! $storagePath) {
    $defaultStorage = dirname(__DIR__).'/storage';

    if (! is_writable($defaultStorage)) {
        $storagePath = sys_get_temp_dir().'/laravel-storage';
    }
}

if ($storagePath) {
    $app->useStoragePath($storagePath);

    $directories = [
        'framework',
        'framework/cache',
        'framework/cache/data',
        'framework/sessions',
        'framework/views',
        'logs',
    ];

    foreach ($directories as $directory) {
        $path = $storagePath.'/'.$directory;

        if (! is_dir($path)) {
            @mkdir($path, 0755, true);
        }
    }
}

return $app;
