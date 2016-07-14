<?php

namespace Zephia\ZLeader\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

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
        $this->loadViewsFrom(__DIR__.'/../../resources/views', $this->packageName);
        $this->setupRoutes($this->app->router);

        $this->publishes([
            __DIR__.'/../../config/zleader.php' => config_path('zleader.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../resources/views' => base_path('resources/views/vendor/' . $this->packageName),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../public/zl.js' => public_path('zl.js'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/' . $this->packageName . '/assets'),
            __DIR__.'/../../vendor/cartalyst/data-grid/public/js' => public_path('vendor/' . $this->packageName . '/cartalyst/data-grid/js'),
            __DIR__.'/../../vendor/almasaeed2010/adminlte/dist' => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/dist'),
            __DIR__.'/../../vendor/almasaeed2010/adminlte/plugins' => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/plugins'),
            __DIR__.'/../../vendor/almasaeed2010/adminlte/bootstrap' => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/bootstrap'),
            __DIR__.'/../../vendor/jan-dolata/crude-crud/src/public' => public_path('vendor/jan-dolata/crude-crud'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');        
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
        $this->mergeConfigFrom(__DIR__.'/../../config/crude.php', 'crude');
        $this->mergeConfigFrom(__DIR__.'/../../config/sluggable.php', 'sluggable');

        $this->registerProviders();
        $this->registerAliases();
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Zephia\ZLeader\Http\Controllers'], function($router) {
            include __DIR__.'/../Http/routes.php';
        });
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
