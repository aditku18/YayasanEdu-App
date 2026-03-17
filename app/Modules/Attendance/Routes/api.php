<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Attendance\Http\Controllers\Api\AttendanceApiController;
use App\Modules\Attendance\Http\Controllers\Api\QrCodeController;
use App\Modules\Attendance\Http\Controllers\Api\FingerprintController;
use App\Modules\Attendance\Http\Controllers\Api\FaceRecognitionController;
use App\Modules\Attendance\Http\Controllers\Api\RfidController;
use App\Modules\Attendance\Http\Controllers\Api\GpsController;
use App\Modules\Attendance\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| Attendance API Routes
|--------------------------------------------------------------------------
|
| RESTful API endpoints for attendance system
|
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    
    // Core Attendance Routes
    Route::prefix('attendance')->group(function () {
        
        // Clock In/Out
        Route::post('/clock-in', [AttendanceApiController::class, 'clockIn']);
        Route::post('/clock-out', [AttendanceApiController::class, 'clockOut']);
        
        // Status and History
        Route::get('/status', [AttendanceApiController::class, 'getStatus']);
        Route::get('/history', [AttendanceApiController::class, 'getHistory']);
        Route::get('/today', [AttendanceApiController::class, 'getTodayAttendance']);
        
        // Manual Operations
        Route::post('/mark-absent', [AttendanceApiController::class, 'markAbsent']);
        Route::post('/mark-excused', [AttendanceApiController::class, 'markExcused']);
        
        // Sessions
        Route::get('/sessions', [AttendanceApiController::class, 'getSessions']);
        Route::get('/sessions/active', [AttendanceApiController::class, 'getActiveSession']);
    });

    // QR Code Routes
    Route::prefix('attendance/qr')->group(function () {
        Route::post('/generate', [QrCodeController::class, 'generate']);
        Route::post('/validate-qr', [QrCodeController::class, 'validateQrCode']);
        Route::post('/clock-in', [QrCodeController::class, 'clockInWithQr']);
        Route::post('/clock-out', [QrCodeController::class, 'clockOutWithQr']);
    });

    // Fingerprint Routes
    Route::prefix('attendance/fingerprint')->group(function () {
        Route::post('/enroll', [FingerprintController::class, 'enroll']);
        Route::post('/verify', [FingerprintController::class, 'verify']);
        Route::get('/user/{userId}', [FingerprintController::class, 'getUserFingerprints']);
        Route::delete('/{fingerprintId}', [FingerprintController::class, 'delete']);
    });

    // Face Recognition Routes
    Route::prefix('attendance/face')->group(function () {
        Route::post('/enroll', [FaceRecognitionController::class, 'enroll']);
        Route::post('/verify', [FaceRecognitionController::class, 'verify']);
        Route::get('/user/{userId}', [FaceRecognitionController::class, 'getUserFace']);
        Route::delete('/{faceId}', [FaceRecognitionController::class, 'delete']);
        Route::get('/liveness/challenge', [FaceRecognitionController::class, 'getLivenessChallenge']);
    });

    // RFID Routes
    Route::prefix('attendance/rfid')->group(function () {
        Route::post('/enroll', [RfidController::class, 'enroll']);
        Route::post('/verify', [RfidController::class, 'verify']);
        Route::get('/user/{userId}', [RfidController::class, 'getUserCards']);
        Route::delete('/{rfidId}', [RfidController::class, 'delete']);
        Route::post('/{rfidId}/blacklist', [RfidController::class, 'blacklist']);
        Route::post('/{rfidId}/unblacklist', [RfidController::class, 'unblacklist']);
    });

    // GPS / Geofence Routes
    Route::prefix('attendance/gps')->group(function () {
        Route::post('/verify-location', [GpsController::class, 'verifyLocation']);
        Route::post('/clock-in', [GpsController::class, 'clockInWithGps']);
        Route::post('/clock-out', [GpsController::class, 'clockOutWithGps']);
        
        // Geofence Management
        Route::get('/geofences', [GpsController::class, 'getGeofences']);
        Route::post('/geofences', [GpsController::class, 'createGeofence']);
        Route::put('/geofences/{id}', [GpsController::class, 'updateGeofence']);
        Route::delete('/geofences/{id}', [GpsController::class, 'deleteGeofence']);
    });

    // Report Routes
    Route::prefix('attendance/reports')->group(function () {
        Route::post('/generate', [ReportController::class, 'generate']);
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/{id}', [ReportController::class, 'show']);
        Route::get('/{id}/download', [ReportController::class, 'download']);
        Route::delete('/{id}', [ReportController::class, 'delete']);
        
        // Dashboard Stats
        Route::get('/dashboard/stats', [ReportController::class, 'getDashboardStats']);
    });

    // Backup Routes
    Route::prefix('attendance/backup')->group(function () {
        Route::post('/create', [ReportController::class, 'createBackup']);
        Route::get('/', [ReportController::class, 'listBackups']);
        Route::post('/restore', [ReportController::class, 'restoreBackup']);
        Route::delete('/', [ReportController::class, 'deleteBackup']);
    });
});

// Public routes (for device authentication)
Route::prefix('attendance/device')->group(function () {
    Route::post('/register', [AttendanceApiController::class, 'registerDevice']);
    Route::post('/heartbeat', [AttendanceApiController::class, 'deviceHeartbeat']);
});
