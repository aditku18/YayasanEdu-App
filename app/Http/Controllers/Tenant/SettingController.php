<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function notification()
    {
        // Logic for notification settings
        return view('tenant.settings.notification');
    }

    public function updateNotification(Request $request)
    {
        // Validate and update notification settings
        $validated = $request->validate([
            'email_new_user' => 'sometimes|boolean',
            'email_payment' => 'sometimes|boolean',
            'email_system' => 'sometimes|boolean',
            'sms_payment' => 'sometimes|boolean',
            'sms_urgent' => 'sometimes|boolean',
            'push_announcements' => 'sometimes|boolean',
            'push_reminders' => 'sometimes|boolean',
        ]);

        // Save settings to database or config
        // For now, just redirect with success message
        return redirect()->route('tenant.setting.notification')->with('success', 'Pengaturan notifikasi berhasil disimpan!');
    }

    public function security()
    {
        // Logic for security settings
        return view('tenant.settings.security');
    }

    public function updateSecurity(Request $request)
    {
        // Validate and update security settings
        $validated = $request->validate([
            'min_password_length' => 'required|integer|min:6|max:20',
            'require_uppercase' => 'sometimes|boolean',
            'require_numbers' => 'sometimes|boolean',
            'require_symbols' => 'sometimes|boolean',
            'session_timeout' => 'required|integer|min:5|max:480',
            'require_2fa' => 'sometimes|boolean',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
        ]);

        // Save settings to database or config
        return redirect()->route('tenant.setting.security')->with('success', 'Pengaturan keamanan berhasil disimpan!');
    }

    public function backup()
    {
        // Logic for backup settings
        $lastBackup = null; // Get last backup info from database
        return view('tenant.settings.backup', compact('lastBackup'));
    }

    public function updateBackup(Request $request)
    {
        // Validate and update backup settings
        $validated = $request->validate([
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'backup_time' => 'required|date_format:H:i',
            'backup_database' => 'sometimes|boolean',
            'backup_files' => 'sometimes|boolean',
            'retention_days' => 'required|integer|min:7|max:365',
            'backup_location' => 'required|in:local,cloud',
        ]);

        // Save settings to database or config
        return redirect()->route('tenant.setting.backup')->with('success', 'Pengaturan backup berhasil disimpan!');
    }

    public function createBackup(Request $request)
    {
        try {
            // Logic to create backup
            // This would typically dispatch a job or run backup process
            
            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dimulai!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
