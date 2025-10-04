<?php

namespace Ayoub\SiteLock;

use Illuminate\Support\ServiceProvider;
use Ayoub\SiteLock\Http\Middleware\SiteLockMiddleware;

class SiteLockServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/site-lock.php', 'site-lock');
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/site-lock.php' => config_path('site-lock.php'),
        ], 'site-lock-config');

        // Publish views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'site-lock');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/site-lock'),
        ], 'site-lock-views');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Register middleware
        $this->app['router']->aliasMiddleware('site-lock', SiteLockMiddleware::class);
    }
}