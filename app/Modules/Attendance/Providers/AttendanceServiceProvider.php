<?php

namespace App\Modules\Attendance\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Attendance\Services\AttendanceService;
use App\Modules\Attendance\Services\QrCodeService;
use App\Modules\Attendance\Services\FingerprintService;
use App\Modules\Attendance\Services\FaceRecognitionService;
use App\Modules\Attendance\Services\RfidService;
use App\Modules\Attendance\Services\GpsAttendanceService;
use App\Modules\Attendance\Services\ReportService;
use App\Modules\Attendance\Services\BackupService;

class AttendanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register singleton services
        $this->app->singleton(AttendanceService::class, function ($app) {
            return new AttendanceService();
        });

        $this->app->singleton(QrCodeService::class, function ($app) {
            return new QrCodeService();
        });

        $this->app->singleton(FingerprintService::class, function ($app) {
            return new FingerprintService();
        });

        $this->app->singleton(FaceRecognitionService::class, function ($app) {
            return new FaceRecognitionService();
        });

        $this->app->singleton(RfidService::class, function ($app) {
            return new RfidService();
        });

        $this->app->singleton(GpsAttendanceService::class, function ($app) {
            return new GpsAttendanceService();
        });

        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService();
        });

        $this->app->singleton(BackupService::class, function ($app) {
            return new BackupService();
        });

        // Register aliases
        $this->app->alias(AttendanceService::class, 'attendance.service');
        $this->app->alias(QrCodeService::class, 'attendance.qrcode');
        $this->app->alias(FingerprintService::class, 'attendance.fingerprint');
        $this->app->alias(FaceRecognitionService::class, 'attendance.face');
        $this->app->alias(RfidService::class, 'attendance.rfid');
        $this->app->alias(GpsAttendanceService::class, 'attendance.gps');
        $this->app->alias(ReportService::class, 'attendance.report');
        $this->app->alias(BackupService::class, 'attendance.backup');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'attendance');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'attendance');

        // Publish config
        $this->publishes([
            __DIR__ . '/../Config/attendance.php' => config_path('attendance.php'),
        ], 'attendance-config');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('attendance'),
        ], 'attendance-assets');
    }
}
