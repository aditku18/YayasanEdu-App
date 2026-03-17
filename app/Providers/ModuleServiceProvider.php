<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/**
 * Module Service Provider
 * 
 * Automatically discovers and loads modules from app/Modules directory.
 * 
 * Each module should follow this structure:
 * app/Modules/{ModuleName}/
 * ├── Http/
 * │   ├── Controllers/
 * │   └── Requests/
 * ├── Models/
 * ├── Routes/
 * │   └── web.php
 * ├── Views/
 * └── Database/
 *     └── migrations/
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Registered modules
     *
     * @var array
     */
    protected $modules = [];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->modules = $this->getModules();
        
        foreach ($this->modules as $module) {
            $this->registerModule($module);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->modules = $this->getModules();
        
        foreach ($this->modules as $module) {
            $this->bootModule($module);
        }
    }

    /**
     * Get all available modules
     *
     * @return array
     */
    protected function getModules(): array
    {
        $modulesPath = base_path('app/Modules');
        
        if (!File::exists($modulesPath)) {
            return [];
        }

        $modules = [];
        
        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);
            
            // Only include directories that have Http/Controllers
            if (File::exists($modulePath . '/Http/Controllers')) {
                $modules[$moduleName] = [
                    'path' => $modulePath,
                    'name' => $moduleName,
                    'lower' => strtolower($moduleName),
                ];
            }
        }

        return $modules;
    }

    /**
     * Register module services
     *
     * @param array $module
     */
    protected function registerModule(array $module): void
    {
        $modulePath = $module['path'];
        
        // Register module config if exists
        $configPath = $modulePath . '/Config/config.php';
        if (File::exists($configPath)) {
            $configKey = 'modules.' . strtolower($module['name']);
            $this->mergeConfigFrom($configPath, $configKey);
        }
    }

    /**
     * Boot module
     *
     * @param array $module
     */
    protected function bootModule(array $module): void
    {
        $modulePath = $module['path'];
        $moduleName = $module['name'];
        $moduleLower = $module['lower'];

        // Load routes
        $this->loadRoutes($modulePath, $moduleName);

        // Load migrations
        $this->loadMigrations($modulePath);

        // Load views
        $this->loadViews($modulePath, $moduleName);

        // Load translations
        $this->loadTranslations($modulePath, $moduleName);

        // Publish assets
        $this->publishAssets($modulePath, $moduleName);
    }

    /**
     * Load module routes
     *
     * @param string $modulePath
     * @param string $moduleName
     */
    protected function loadRoutes(string $modulePath, string $moduleName): void
    {
        $webRoutesPath = $modulePath . '/Routes/web.php';
        
        if (File::exists($webRoutesPath)) {
            Route::middleware('web')
                ->group($webRoutesPath);
        }

        $apiRoutesPath = $modulePath . '/Routes/api.php';
        
        if (File::exists($apiRoutesPath)) {
            Route::middleware('api')
                ->prefix('api')
                ->group($apiRoutesPath);
        }
    }

    /**
     * Load module migrations
     *
     * @param string $modulePath
     */
    protected function loadMigrations(string $modulePath): void
    {
        $migrationsPath = $modulePath . '/Database/migrations';
        
        if (File::exists($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    /**
     * Load module views
     *
     * @param string $modulePath
     * @param string $moduleName
     */
    protected function loadViews(string $modulePath, string $moduleName): void
    {
        $viewsPath = $modulePath . '/Views';
        
        if (File::exists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, strtolower($moduleName));
        }
    }

    /**
     * Load module translations
     *
     * @param string $modulePath
     * @param string $moduleName
     */
    protected function loadTranslations(string $modulePath, string $moduleName): void
    {
        $langPath = $modulePath . '/Resources/lang';
        
        if (File::exists($langPath)) {
            $this->loadTranslationsFrom($langPath, strtolower($moduleName));
        }
    }

    /**
     * Publish module assets
     *
     * @param string $modulePath
     * @param string $moduleName
     */
    protected function publishAssets(string $modulePath, string $moduleName): void
    {
        $assetsPath = $modulePath . '/Assets';
        
        if (File::exists($assetsPath)) {
            $this->publishes([
                $assetsPath => public_path('modules/' . strtolower($moduleName)),
            ], 'module-assets');
        }

        $configPath = $modulePath . '/Config/config.php';
        
        if (File::exists($configPath)) {
            $this->publishes([
                $configPath => config_path('modules/' . strtolower($moduleName) . '.php'),
            ], 'module-config');
        }
    }

    /**
     * Get all registered modules
     *
     * @return array
     */
    public function getAllModules(): array
    {
        return $this->modules;
    }

    /**
     * Check if a module exists
     *
     * @param string $moduleName
     * @return bool
     */
    public function hasModule(string $moduleName): bool
    {
        return isset($this->modules[$moduleName]);
    }

    /**
     * Get module path
     *
     * @param string $moduleName
     * @return string|null
     */
    public function getModulePath(string $moduleName): ?string
    {
        return $this->modules[$moduleName]['path'] ?? null;
    }
}
