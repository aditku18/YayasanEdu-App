<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Attendance\Http\Controllers\Admin\AttendanceController;
use App\Modules\Attendance\Http\Controllers\Admin\DeviceController;
use App\Modules\Attendance\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Modules\Attendance\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Attendance Web Routes
|--------------------------------------------------------------------------
|
| Web routes for attendance management (admin panel)
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Attendance Module Routes
    Route::prefix('attendance')->group(function () {
        
        // Dashboard
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.dashboard');
        Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
        
        // Attendance Records
        Route::get('/records', [AttendanceController::class, 'records'])->name('attendance.records');
        Route::get('/records/{id}', [AttendanceController::class, 'showRecord'])->name('attendance.records.show');
        Route::get('/records/export', [AttendanceController::class, 'exportRecords'])->name('attendance.records.export');
        
        // Sessions
        Route::get('/sessions', [AttendanceController::class, 'sessions'])->name('attendance.sessions');
        Route::post('/sessions', [AttendanceController::class, 'storeSession'])->name('attendance.sessions.store');
        Route::put('/sessions/{id}', [AttendanceController::class, 'updateSession'])->name('attendance.sessions.update');
        Route::delete('/sessions/{id}', [AttendanceController::class, 'destroySession'])->name('attendance.sessions.destroy');
        Route::post('/sessions/{id}/toggle', [AttendanceController::class, 'toggleSession'])->name('attendance.sessions.toggle');
        
        // QR Code Management
        Route::get('/qr-codes', [AttendanceController::class, 'qrCodes'])->name('attendance.qr-codes');
        Route::post('/qr-codes/generate', [AttendanceController::class, 'generateQrCode'])->name('attendance.qr-codes.generate');
        
        // User Management
        Route::get('/users', [AttendanceController::class, 'users'])->name('attendance.users');
        Route::get('/users/{userId}/attendance', [AttendanceController::class, 'userAttendance'])->name('attendance.users.attendance');
        
        // Biometric Management
        Route::get('/fingerprints', [AttendanceController::class, 'fingerprints'])->name('attendance.fingerprints');
        Route::get('/faces', [AttendanceController::class, 'faces'])->name('attendance.faces');
        Route::get('/rfids', [AttendanceController::class, 'rfids'])->name('attendance.rfids');
        
        // Devices
        Route::get('/devices', [DeviceController::class, 'index'])->name('attendance.devices');
        Route::post('/devices', [DeviceController::class, 'store'])->name('attendance.devices.store');
        Route::put('/devices/{id}', [DeviceController::class, 'update'])->name('attendance.devices.update');
        Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('attendance.devices.destroy');
        Route::post('/devices/{id}/toggle', [DeviceController::class, 'toggle'])->name('attendance.devices.toggle');
        
        // Geofences
        Route::get('/geofences', [AttendanceController::class, 'geofences'])->name('attendance.geofences');
        Route::post('/geofences', [AttendanceController::class, 'storeGeofence'])->name('attendance.geofences.store');
        Route::put('/geofences/{id}', [AttendanceController::class, 'updateGeofence'])->name('attendance.geofences.update');
        Route::delete('/geofences/{id}', [AttendanceController::class, 'destroyGeofence'])->name('attendance.geofences.destroy');
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('attendance.reports');
        Route::get('/reports/create', [AdminReportController::class, 'create'])->name('attendance.reports.create');
        Route::post('/reports', [AdminReportController::class, 'store'])->name('attendance.reports.store');
        Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('attendance.reports.show');
        Route::get('/reports/{id}/download', [AdminReportController::class, 'download'])->name('attendance.reports.download');
        Route::delete('/reports/{id}', [AdminReportController::class, 'destroy'])->name('attendance.reports.destroy');
        
        // Backup & Maintenance
        Route::get('/backup', [AdminReportController::class, 'backup'])->name('attendance.backup');
        Route::post('/backup/create', [AdminReportController::class, 'createBackup'])->name('attendance.backup.create');
        Route::post('/backup/restore', [AdminReportController::class, 'restoreBackup'])->name('attendance.backup.restore');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('attendance.settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('attendance.settings.update');
        
        // Audit Logs
        Route::get('/audit-logs', [AttendanceController::class, 'auditLogs'])->name('attendance.audit-logs');
    });
});

// User-facing attendance routes (clock in/out)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/clock-in', [AttendanceController::class, 'clockInPage'])->name('attendance.clock-in');
    Route::get('/clock-out', [AttendanceController::class, 'clockOutPage'])->name('attendance.clock-out');
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
});
