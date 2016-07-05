<?php

namespace Zephia\ZLeader\Providers;

use Illuminate\Support\ServiceProvider;

class ZLeaderServiceProvider extends ServiceProvider
{
    protected $packageName = 'ZLeader';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/zleader.php' => config_path('zleader.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../../resources/views' => base_path('resources/views/vendor/' . $this->packageName),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/' . $this->packageName . '/assets'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../vendor/cartalyst/data-grid/public/js' => public_path('vendor/' . $this->packageName . '/cartalyst/data-grid/js'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $this->mergeConfigFrom(__DIR__.'/../../config/zleader.php', $this->packageName);
        $this->loadViewsFrom(__DIR__.'/../../resources/views', $this->packageName);

        $this->registerProviders();
        $this->registerAliases();;

        include __DIR__.'/../Http/routes.php';
        $this->app->make(\Zephia\ZLeader\Http\Controllers\ZLeaderController::class);
    }

    public function registerProviders()
    {
        foreach (config($this->packageName . '.providers', []) as $providerClass) {
            $this->app->register($providerClass);
        }
    }

    public function registerAliases()
    {
        foreach (config($this->packageName . '.aliases', []) as $aliasName => $aliasClass) {
            \Illuminate\Foundation\AliasLoader::getInstance()->alias($aliasName, $aliasClass);
        }
    }
}
