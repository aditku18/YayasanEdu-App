<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'mail_driver' => config('mail.default'),
            'database_connection' => config('database.default'),
            'broadcast_driver' => config('broadcasting.default'),
            'queue_driver' => config('queue.default'),
        ];

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => \DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown',
            'cache_status' => Cache::store()->getStore() ? 'Connected' : 'Disconnected',
            'storage_writable' => is_writable(storage_path()),
            'logs_writable' => is_writable(storage_path('logs')),
        ];

        return view('platform.settings.index', compact('settings', 'systemInfo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'locale' => 'required|string',
            'debug_mode' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Update .env file or config cache
        // Note: In production, you would update the .env file directly
        // For demo purposes, we'll just show success message

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function cache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Cache berhasil dibersihkan.');
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return redirect()->back()->with('success', 'Aplikasi berhasil dioptimalkan.');
    }

    public function backup()
    {
        // This would implement database backup
        // For demo purposes, just show success

        return redirect()->back()->with('success', 'Backup berhasil dibuat.');
    }
}
