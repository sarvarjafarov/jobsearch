<?php

namespace App\Providers;

use App\Models\Job;
use App\Observers\JobObserver;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $viewPath = resource_path('views');
        $configuredPaths = config('view.paths', []);

        if (! in_array($viewPath, $configuredPaths, true)) {
            config(['view.paths' => array_merge([$viewPath], $configuredPaths)]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ViewFactory $view): void
    {
        Job::observe(JobObserver::class);

        $viewPath = resource_path('views');

        if (! in_array($viewPath, $view->getFinder()->getPaths(), true)) {
            $view->addLocation($viewPath);
        }
    }
}
