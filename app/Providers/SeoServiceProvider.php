<?php

namespace App\Providers;

use App\Services\SeoManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SeoManager::class, fn ($app) => new SeoManager());
    }

    public function boot(): void
    {
        View::composer('layout', function ($view) {
            $manager = app(SeoManager::class);
            $view->with('seoData', $manager->resolve(request()));
        });
    }
}
