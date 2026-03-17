<?php

namespace App\Plugins\PPDB;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class PPDBServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge plugin configuration
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'ppdb'
        );

        // Register PPDB Service
        $this->app->singleton(\App\Plugins\PPDB\Services\PPDBService::class, function ($app) {
            return new \App\Plugins\PPDB\Services\PPDBService();
        });

        // Register Payment Service
        $this->app->singleton(\App\Plugins\PPDB\Services\PaymentService::class, function ($app) {
            return new \App\Plugins\PPDB\Services\PaymentService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'ppdb');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/Lang', 'ppdb');

        // Publish assets
        $this->publishes([
            __DIR__.'/Resources/assets' => public_path('plugins/ppdb'),
        ], ['ppdb-assets', 'public']);

        // Publish config
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('ppdb.php'),
        ], 'ppdb-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/Database/Migrations' => database_path('migrations'),
        ], 'ppdb-migrations');

        // Register middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('plugin.ppdb', \App\Plugins\PPDB\Http\Middleware\CheckPPDBInstallation::class);

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Plugins\PPDB\Commands\InstallPPDB::class,
                \App\Plugins\PPDB\Commands\UninstallPPDB::class,
                \App\Plugins\PPDB\Commands\MigratePPDBData::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            \App\Plugins\PPDB\Services\PPDBService::class,
            \App\Plugins\PPDB\Services\PaymentService::class,
        ];
    }
}
