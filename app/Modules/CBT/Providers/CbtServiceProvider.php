<?php

namespace App\Modules\CBT\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\CBT\Services\CourseService;
use App\Modules\CBT\Services\QuizService;
use App\Modules\CBT\Services\GradingService;
use App\Modules\CBT\Services\ProgressService;
use App\Modules\CBT\Services\CertificateService;
use App\Modules\CBT\Services\AnalyticsService;

class CbtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register singleton services
        $this->app->singleton(CourseService::class, function ($app) {
            return new CourseService();
        });

        $this->app->singleton(QuizService::class, function ($app) {
            return new QuizService();
        });

        $this->app->singleton(GradingService::class, function ($app) {
            return new GradingService();
        });

        $this->app->singleton(ProgressService::class, function ($app) {
            return new ProgressService();
        });

        $this->app->singleton(CertificateService::class, function ($app) {
            return new CertificateService();
        });

        $this->app->singleton(AnalyticsService::class, function ($app) {
            return new AnalyticsService();
        });

        // Register aliases
        $this->app->alias(CourseService::class, 'cbt.course');
        $this->app->alias(QuizService::class, 'cbt.quiz');
        $this->app->alias(GradingService::class, 'cbt.grading');
        $this->app->alias(ProgressService::class, 'cbt.progress');
        $this->app->alias(CertificateService::class, 'cbt.certificate');
        $this->app->alias(AnalyticsService::class, 'cbt.analytics');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'cbt');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'cbt');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/cbt.php' => config_path('cbt.php'),
        ], 'cbt-config');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('cbt'),
        ], 'cbt-assets');
    }
}
