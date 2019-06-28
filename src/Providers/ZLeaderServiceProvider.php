<?php

namespace Zephia\ZLeader\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;

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
        $this->setupRoutesApi($this->app->router);

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
            base_path('vendor/almasaeed2010/adminlte/dist') => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/dist'),
            base_path('vendor/almasaeed2010/adminlte/plugins') => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/plugins'),
            base_path('vendor/almasaeed2010/adminlte/bootstrap') => public_path('vendor/' . $this->packageName . '/almasaeed2010/adminlte/bootstrap'),
            base_path('vendor/jan-dolata/crude-crud/public') => public_path('vendor/jan-dolata/crude-crud'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('autocity:release-queue')->everyMinute();
        });

        $bindings = app()->getBindings();
        if (empty($bindings['user'])) {
            app()->bind('user', function () {
                return false;
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/zleader.php', $this->packageName);
        $this->mergeConfigFrom(__DIR__.'/../../config/crude.php', 'crude');
        $this->mergeConfigFrom(__DIR__.'/../../config/sluggable.php', 'sluggable');
        $this->mergeConfigFrom(__DIR__.'/../../config/laravel-facebook-sdk.php', 'laravel-facebook-sdk');

        $this->registerProviders();
        $this->registerAliases();
        $this->registerCommands();
        $this->registerMiddlewares();
    }

    protected function registerCommands()
    {
        $this->commands(config($this->packageName . '.commands', []));
    }

    protected function registerMiddlewares()
    {
        foreach (config($this->packageName . '.middlewares', []) as $middlewareName => $middlewareClass) {
            $this->app['router']->aliasMiddleware($middlewareName, $middlewareClass);
        }
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Zephia\ZLeader\Http\Controllers'], function($router) {
            include __DIR__.'/../Http/routes.php';
        });
    }

    public function setupRoutesApi(Router $router)
    {
        $router->group(['namespace' => 'Zephia\ZLeader\Http\Controllers'], function($router) {
            include __DIR__.'/../Http/routes_api.php';
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
