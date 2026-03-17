<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PPDB Module Routes
|--------------------------------------------------------------------------
|
| Routes for the PPDB (Penerimaan Peserta Didik Baru) module.
| NOTE: Routes are commented out - controllers not yet implemented
|
*/

// Route::prefix('ppdb')->name('ppdb.')->group(function () {
    
//     // Public Routes (No Auth Required)
//     Route::prefix('public')->name('public.')->group(function () {
//         Route::get('/', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'index'])->name('index');
//         Route::get('/register/{wave?}', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'register'])->name('register');
//         Route::post('/register', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'store'])->name('store');
//         Route::get('/success/{applicant}', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'success'])->name('success');
//         Route::get('/check-status', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'checkStatus'])->name('check-status');
//         Route::post('/tracking', [\App\Modules\PPDB\Http\Controllers\PublicController::class, 'tracking'])->name('tracking');
//     });
    
//     // Admin Routes (Auth Required)
//     Route::middleware(['auth'])->group(function () {
        
//         // Dashboard
//         Route::get('/dashboard', [\App\Modules\PPDB\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        
//         // Waves (Gelombang)
//         Route::prefix('waves')->name('waves.')->group(function () {
//             Route::get('/', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'index'])->name('index');
//             Route::post('/', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'store'])->name('store');
//             Route::put('/{wave}', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'update'])->name('update');
//             Route::delete('/{wave}', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'destroy'])->name('destroy');
//             Route::post('/{wave}/toggle', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'toggleStatus'])->name('toggle');
            
//             // Fees for wave
//             Route::get('/{wave}/fees', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'fees'])->name('fees');
//             Route::post('/{wave}/fees', [\App\Modules\PPDB\Http\Controllers\WaveController::class, 'updateFees'])->name('fees.update');
//         });
        
//         // Applicants (Calon Siswa)
//         Route::prefix('applicants')->name('applicants.')->group(function () {
//             Route::get('/', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'index'])->name('index');
//             Route::get('/{applicant}', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'show'])->name('show');
//             Route::post('/{applicant}/verify', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'verify'])->name('verify');
//             Route::post('/{applicant}/approve', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'approve'])->name('approve');
//             Route::post('/{applicant}/reject', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'reject'])->name('reject');
//             Route::post('/{applicant}/verify-payment', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'verifyPayment'])->name('verify-payment');
//             Route::post('/{applicant}/accept', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'accept'])->name('accept');
            
//             // Document upload
//             Route::post('/{applicant}/documents', [\App\Modules\PPDB\Http\Controllers\ApplicantController::class, 'uploadDocuments'])->name('documents');
//         });
        
//         // Settings
//         Route::get('/settings', [\App\Modules\PPDB\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
//         Route::put('/settings', [\App\Modules\PPDB\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        
//         // Reports
//         Route::get('/reports', [\App\Modules\PPDB\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
//         Route::get('/reports/export', [\App\Modules\PPDB\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
//     });
// });
