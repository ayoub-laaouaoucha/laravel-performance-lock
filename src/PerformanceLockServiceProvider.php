<?php

namespace Naqla\PerformanceLock;

use Illuminate\Support\ServiceProvider;
use Naqla\PerformanceLock\Http\Middleware\PerformanceLockMiddleware;

class PerformanceLockServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/performance-lock.php', 'Performance-lock');
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/performance-lock.php' => config_path('performance-lock.php'),
        ], 'performance-lock-config');

        // Publish views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'performance-lock');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/performance-lock'),
        ], 'performance-lock-views');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Register middleware
        $this->app['router']->aliasMiddleware('performance-lock', PerformanceLockMiddleware::class);
    }
}