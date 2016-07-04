<?php

namespace Zephia\ZLeader;

use Illuminate\Support\ServiceProvider;

class ZLeaderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'zleader');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Zephia\ZLeader\ZLeaderController');
    }
}
