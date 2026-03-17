<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\PPDB\Http\Controllers\Api\ApiController;
use App\Plugins\PPDB\Http\Controllers\Api\PublicApiController;

/*
|--------------------------------------------------------------------------
| PPDB Plugin API Routes
|--------------------------------------------------------------------------
|
| API routes for PPDB (Penerimaan Peserta Didik Baru) plugin
| These routes are for external integrations and mobile apps
|
*/

// Public API Routes (No Authentication Required)
Route::prefix('api/v1/ppdb')->name('ppdb.api.public.')->group(function () {
    
    // Get active waves
    Route::get('/waves', [PublicApiController::class, 'getActiveWaves'])->name('waves');
    Route::get('/waves/{wave}', [PublicApiController::class, 'getWaveDetails'])->name('wave.details');
    
    // Registration check
    Route::post('/check-availability', [PublicApiController::class, 'checkRegistrationAvailability'])->name('check.availability');
    Route::post('/generate-registration-number', [PublicApiController::class, 'generateRegistrationNumber'])->name('generate.registration-number');
    
    // Status tracking
    Route::post('/track-status', [PublicApiController::class, 'trackApplicationStatus'])->name('track.status');
    Route::get('/status/{registration_number}', [PublicApiController::class, 'getApplicationStatus'])->name('status');
    
    // Public statistics (if enabled)
    Route::get('/statistics', [PublicApiController::class, 'getPublicStatistics'])->name('statistics');
    
    // School information
    Route::get('/schools', [PublicApiController::class, 'getSchools'])->name('schools');
    Route::get('/schools/{school}/majors', [PublicApiController::class, 'getSchoolMajors'])->name('schools.majors');
});

// Authenticated API Routes (API Token Required)
Route::prefix('api/v1/ppdb')->name('ppdb.api.auth.')->middleware(['auth:api', 'plugin.ppdb'])->group(function () {
    
    // Applicants API
    Route::prefix('applicants')->name('applicants.')->group(function () {
        Route::get('/', [ApiController::class, 'index'])->name('index');
        Route::post('/', [ApiController::class, 'store'])->name('store');
        Route::get('/{applicant}', [ApiController::class, 'show'])->name('show');
        Route::put('/{applicant}', [ApiController::class, 'update'])->name('update');
        Route::delete('/{applicant}', [ApiController::class, 'destroy'])->name('destroy');
        
        // Applicant actions
        Route::post('/{applicant}/verify', [ApiController::class, 'verify'])->name('verify');
        Route::post('/{applicant}/approve', [ApiController::class, 'approve'])->name('approve');
        Route::post('/{applicant}/reject', [ApiController::class, 'reject'])->name('reject');
        Route::post('/{applicant}/verify-payment', [ApiController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/{applicant}/enroll', [ApiController::class, 'enroll'])->name('enroll');
        
        // Documents
        Route::get('/{applicant}/documents', [ApiController::class, 'documents'])->name('documents');
        Route::post('/{applicant}/documents', [ApiController::class, 'uploadDocument'])->name('upload.document');
        Route::delete('/{applicant}/documents/{document}', [ApiController::class, 'deleteDocument'])->name('delete.document');
    });
    
    // Waves API
    Route::prefix('waves')->name('waves.')->group(function () {
        Route::get('/', [ApiController::class, 'wavesIndex'])->name('index');
        Route::post('/', [ApiController::class, 'wavesStore'])->name('store');
        Route::get('/{wave}', [ApiController::class, 'wavesShow'])->name('show');
        Route::put('/{wave}', [ApiController::class, 'wavesUpdate'])->name('update');
        Route::delete('/{wave}', [ApiController::class, 'wavesDestroy'])->name('destroy');
        
        // Wave actions
        Route::post('/{wave}/toggle-status', [ApiController::class, 'toggleWaveStatus'])->name('toggle-status');
        Route::post('/{wave}/duplicate', [ApiController::class, 'duplicateWave'])->name('duplicate');
        
        // Wave fees
        Route::get('/{wave}/fees', [ApiController::class, 'waveFees'])->name('fees');
        Route::post('/{wave}/fees', [ApiController::class, 'updateWaveFees'])->name('fees.update');
        
        // Wave statistics
        Route::get('/{wave}/statistics', [ApiController::class, 'waveStatistics'])->name('statistics');
        Route::get('/{wave}/applicants', [ApiController::class, 'waveApplicants'])->name('applicants');
    });
    
    // Fee Components API
    Route::prefix('fee-components')->name('fee-components.')->group(function () {
        Route::get('/', [ApiController::class, 'feeComponentsIndex'])->name('index');
        Route::post('/', [ApiController::class, 'feeComponentsStore'])->name('store');
        Route::get('/{component}', [ApiController::class, 'feeComponentsShow'])->name('show');
        Route::put('/{component}', [ApiController::class, 'feeComponentsUpdate'])->name('update');
        Route::delete('/{component}', [ApiController::class, 'feeComponentsDestroy'])->name('destroy');
    });
    
    // Reports API
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/applicants', [ApiController::class, 'applicantReport'])->name('applicants');
        Route::get('/payments', [ApiController::class, 'paymentReport'])->name('payments');
        Route::get('/statistics', [ApiController::class, 'statisticsReport'])->name('statistics');
        Route::get('/waves', [ApiController::class, 'wavesReport'])->name('waves');
        
        // Export endpoints
        Route::post('/export/applicants', [ApiController::class, 'exportApplicants'])->name('export.applicants');
        Route::post('/export/payments', [ApiController::class, 'exportPayments'])->name('export.payments');
        Route::post('/export/statistics', [ApiController::class, 'exportStatistics'])->name('export.statistics');
    });
    
    // Settings API
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [ApiController::class, 'settings'])->name('index');
        Route::put('/', [ApiController::class, 'updateSettings'])->name('update');
        
        // Plugin settings
        Route::get('/plugin', [ApiController::class, 'pluginSettings'])->name('plugin');
        Route::put('/plugin', [ApiController::class, 'updatePluginSettings'])->name('plugin.update');
        
        // Email settings
        Route::get('/email', [ApiController::class, 'emailSettings'])->name('email');
        Route::put('/email', [ApiController::class, 'updateEmailSettings'])->name('email.update');
        
        // SMS settings
        Route::get('/sms', [ApiController::class, 'smsSettings'])->name('sms');
        Route::put('/sms', [ApiController::class, 'updateSmsSettings'])->name('sms.update');
        
        // Payment settings
        Route::get('/payment', [ApiController::class, 'paymentSettings'])->name('payment');
        Route::put('/payment', [ApiController::class, 'updatePaymentSettings'])->name('payment.update');
    });
    
    // Statistics API
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/dashboard', [ApiController::class, 'dashboardStats'])->name('dashboard');
        Route::get('/applicants', [ApiController::class, 'applicantStats'])->name('applicants');
        Route::get('/payments', [ApiController::class, 'paymentStats'])->name('payments');
        Route::get('/waves', [ApiController::class, 'waveStats'])->name('waves');
        Route::get('/schools', [ApiController::class, 'schoolStats'])->name('schools');
    });
    
    // Search API
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/applicants', [ApiController::class, 'searchApplicants'])->name('applicants');
        Route::get('/waves', [ApiController::class, 'searchWaves'])->name('waves');
        Route::get('/schools', [ApiController::class, 'searchSchools'])->name('schools');
    });
    
    // Bulk Actions API
    Route::prefix('bulk')->name('bulk.')->group(function () {
        Route::post('/applicants/approve', [ApiController::class, 'bulkApproveApplicants'])->name('approve.applicants');
        Route::post('/applicants/reject', [ApiController::class, 'bulkRejectApplicants'])->name('reject.applicants');
        Route::post('/applicants/verify-payment', [ApiController::class, 'bulkVerifyPayments'])->name('verify-payments');
        Route::post('/applicants/delete', [ApiController::class, 'bulkDeleteApplicants'])->name('delete.applicants');
        Route::post('/waves/delete', [ApiController::class, 'bulkDeleteWaves'])->name('delete.waves');
    });
    
    // Notifications API
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [ApiController::class, 'notifications'])->name('index');
        Route::post('/send', [ApiController::class, 'sendNotification'])->name('send');
        Route::post('/send/bulk', [ApiController::class, 'sendBulkNotification'])->name('send.bulk');
        Route::post('/mark-read/{notification}', [ApiController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [ApiController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
    // File Upload API
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::post('/document', [ApiController::class, 'uploadDocument'])->name('document');
        Route::post('/photo', [ApiController::class, 'uploadPhoto'])->name('photo');
        Route::post('/payment-proof', [ApiController::class, 'uploadPaymentProof'])->name('payment-proof');
        Route::delete('/{file}', [ApiController::class, 'deleteFile'])->name('delete');
    });
    
    // Integration API
    Route::prefix('integration')->name('integration.')->group(function () {
        // Academic system integration
        Route::post('/sync/students', [ApiController::class, 'syncStudentsToAcademic'])->name('sync.students');
        Route::post('/sync/classes', [ApiController::class, 'syncClassesToAcademic'])->name('sync.classes');
        
        // Finance system integration
        Route::post('/sync/billing', [ApiController::class, 'syncBillingToFinance'])->name('sync.billing');
        Route::post('/sync/payments', [ApiController::class, 'syncPaymentsToFinance'])->name('sync.payments');
        
        // External webhooks
        Route::post('/webhook/payment/{gateway}', [ApiController::class, 'paymentWebhook'])->name('webhook.payment');
        Route::post('/webhook/sms/{provider}', [ApiController::class, 'smsWebhook'])->name('webhook.sms');
    });
    
    // Health Check API
    Route::get('/health', [ApiController::class, 'healthCheck'])->name('health');
    Route::get('/version', [ApiController::class, 'version'])->name('version');
    
    // System Info API
    Route::get('/system/info', [ApiController::class, 'systemInfo'])->name('system.info');
    Route::get('/system/logs', [ApiController::class, 'systemLogs'])->name('system.logs');
    Route::get('/system/cache/clear', [ApiController::class, 'clearCache'])->name('system.clear-cache');
});
