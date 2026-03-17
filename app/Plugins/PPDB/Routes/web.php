<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\PPDB\Http\Controllers\PublicController;
use App\Plugins\PPDB\Http\Controllers\AdminController;
use App\Plugins\PPDB\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| PPDB Plugin Web Routes
|--------------------------------------------------------------------------
|
| Web routes for PPDB (Penerimaan Peserta Didik Baru) plugin
|
*/

// Public Routes (No Authentication Required)
Route::prefix('ppdb')->name('ppdb.public.')->group(function () {
    Route::get('/', [PublicController::class, 'index'])->name('index');
    Route::get('/daftar/{wave}', [PublicController::class, 'register'])->name('register');
    Route::post('/daftar', [PublicController::class, 'store'])->name('store');
    Route::get('/success/{registration_number}', [PublicController::class, 'success'])->name('success');
    Route::get('/cek-status', [PublicController::class, 'checkStatus'])->name('check-status');
    Route::post('/cek-status', [PublicController::class, 'tracking'])->name('tracking');
    Route::get('/upload/{registration_number}', [PublicController::class, 'upload'])->name('upload');
    Route::post('/upload', [PublicController::class, 'storeDocuments'])->name('store-docs');
});

// Admin Routes (Authentication Required)
Route::prefix('admin/ppdb')->name('ppdb.admin.')->middleware(['auth', 'plugin.ppdb'])->group(function () {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Applicants Management
    Route::prefix('applicants')->name('applicants.')->group(function () {
        Route::get('/', [AdminController::class, 'applicants'])->name('index');
        Route::get('/create', [AdminController::class, 'createApplicant'])->name('create');
        Route::post('/', [AdminController::class, 'storeApplicant'])->name('store');
        Route::get('/{applicant}', [AdminController::class, 'showApplicant'])->name('show');
        Route::get('/{applicant}/edit', [AdminController::class, 'editApplicant'])->name('edit');
        Route::put('/{applicant}', [AdminController::class, 'updateApplicant'])->name('update');
        Route::delete('/{applicant}', [AdminController::class, 'deleteApplicant'])->name('delete');
        
        // Applicant Actions
        Route::post('/{applicant}/verify', [AdminController::class, 'verifyApplicant'])->name('verify');
        Route::post('/{applicant}/approve', [AdminController::class, 'approveApplicant'])->name('approve');
        Route::post('/{applicant}/reject', [AdminController::class, 'rejectApplicant'])->name('reject');
        Route::post('/{applicant}/verify-payment', [AdminController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/{applicant}/accept', [AdminController::class, 'acceptApplicant'])->name('accept');
        Route::post('/{applicant}/enroll', [AdminController::class, 'enrollApplicant'])->name('enroll');
        
        // Document Management
        Route::get('/{applicant}/documents', [AdminController::class, 'showDocuments'])->name('documents');
        Route::post('/{applicant}/documents', [AdminController::class, 'uploadDocuments'])->name('upload-documents');
        Route::delete('/{applicant}/documents/{document}', [AdminController::class, 'deleteDocument'])->name('delete-document');
        
        // Communication
        Route::post('/{applicant}/send-email', [AdminController::class, 'sendEmail'])->name('send-email');
        Route::post('/{applicant}/send-sms', [AdminController::class, 'sendSMS'])->name('send-sms');
        
        // Export
        Route::get('/export', [AdminController::class, 'exportApplicants'])->name('export');
    });
    
    // Waves Management
    Route::prefix('waves')->name('waves.')->group(function () {
        Route::get('/', [AdminController::class, 'waves'])->name('index');
        Route::get('/create', [AdminController::class, 'createWave'])->name('create');
        Route::post('/', [AdminController::class, 'storeWave'])->name('store');
        Route::get('/{wave}', [AdminController::class, 'showWave'])->name('show');
        Route::get('/{wave}/edit', [AdminController::class, 'editWave'])->name('edit');
        Route::put('/{wave}', [AdminController::class, 'updateWave'])->name('update');
        Route::delete('/{wave}', [AdminController::class, 'deleteWave'])->name('delete');
        
        // Wave Actions
        Route::post('/{wave}/toggle-status', [AdminController::class, 'toggleWaveStatus'])->name('toggle-status');
        Route::post('/{wave}/duplicate', [AdminController::class, 'duplicateWave'])->name('duplicate');
        Route::post('/{wave}/archive', [AdminController::class, 'archiveWave'])->name('archive');
        
        // Wave Fees
        Route::get('/{wave}/fees', [AdminController::class, 'waveFees'])->name('fees');
        Route::post('/{wave}/fees', [AdminController::class, 'updateWaveFees'])->name('fees.update');
        Route::post('/{wave}/fees/import', [AdminController::class, 'importFees'])->name('fees.import');
        
        // Wave Statistics
        Route::get('/{wave}/statistics', [AdminController::class, 'waveStatistics'])->name('statistics');
        Route::get('/{wave}/applicants', [AdminController::class, 'waveApplicants'])->name('applicants');
    });
    
    // Fee Components Management
    Route::prefix('fee-components')->name('fee-components.')->group(function () {
        Route::get('/', [AdminController::class, 'feeComponents'])->name('index');
        Route::get('/create', [AdminController::class, 'createFeeComponent'])->name('create');
        Route::post('/', [AdminController::class, 'storeFeeComponent'])->name('store');
        Route::get('/{component}/edit', [AdminController::class, 'editFeeComponent'])->name('edit');
        Route::put('/{component}', [AdminController::class, 'updateFeeComponent'])->name('update');
        Route::delete('/{component}', [AdminController::class, 'deleteFeeComponent'])->name('delete');
        Route::post('/{component}/toggle-status', [AdminController::class, 'toggleFeeComponentStatus'])->name('toggle-status');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::get('/applicants', [AdminController::class, 'applicantReport'])->name('applicants');
        Route::get('/payments', [AdminController::class, 'paymentReport'])->name('payments');
        Route::get('/statistics', [AdminController::class, 'statisticsReport'])->name('statistics');
        Route::get('/waves', [AdminController::class, 'wavesReport'])->name('waves');
        
        // Export Actions
        Route::post('/export/applicants', [AdminController::class, 'exportApplicantReport'])->name('export.applicants');
        Route::post('/export/payments', [AdminController::class, 'exportPaymentReport'])->name('export.payments');
        Route::post('/export/statistics', [AdminController::class, 'exportStatisticsReport'])->name('export.statistics');
        Route::post('/export/waves', [AdminController::class, 'exportWavesReport'])->name('export.waves');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::put('/', [AdminController::class, 'updateSettings'])->name('update');
        
        // Plugin Settings
        Route::get('/plugin', [AdminController::class, 'pluginSettings'])->name('plugin');
        Route::put('/plugin', [AdminController::class, 'updatePluginSettings'])->name('plugin.update');
        
        // Email Settings
        Route::get('/email', [AdminController::class, 'emailSettings'])->name('email');
        Route::put('/email', [AdminController::class, 'updateEmailSettings'])->name('email.update');
        Route::post('/email/test', [AdminController::class, 'testEmail'])->name('email.test');
        
        // SMS Settings
        Route::get('/sms', [AdminController::class, 'smsSettings'])->name('sms');
        Route::put('/sms', [AdminController::class, 'updateSmsSettings'])->name('sms.update');
        Route::post('/sms/test', [AdminController::class, 'testSms'])->name('sms.test');
        
        // Payment Settings
        Route::get('/payment', [AdminController::class, 'paymentSettings'])->name('payment');
        Route::put('/payment', [AdminController::class, 'updatePaymentSettings'])->name('payment.update');
        
        // Upload Settings
        Route::get('/uploads', [AdminController::class, 'uploadSettings'])->name('uploads');
        Route::put('/uploads', [AdminController::class, 'updateUploadSettings'])->name('uploads.update');
    });
    
    // Archive Management
    Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [AdminController::class, 'archive'])->name('index');
        Route::post('/run', [AdminController::class, 'runArchive'])->name('run');
        Route::get('/download/{year}', [AdminController::class, 'downloadArchive'])->name('download');
        Route::delete('/{year}', [AdminController::class, 'deleteArchive'])->name('delete');
    });
    
    // Help & Support
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [AdminController::class, 'help'])->name('index');
        Route::get('/documentation', [AdminController::class, 'documentation'])->name('documentation');
        Route::get('/faq', [AdminController::class, 'faq'])->name('faq');
        Route::get('/support', [AdminController::class, 'support'])->name('support');
        Route::post('/support/ticket', [AdminController::class, 'createSupportTicket'])->name('support.ticket');
    });
});

// API Routes (for AJAX calls)
Route::prefix('api/ppdb')->name('ppdb.api.')->middleware(['auth', 'plugin.ppdb'])->group(function () {
    
    // Applicant Statistics (AJAX)
    Route::get('/stats/applicants', [ApiController::class, 'applicantStats'])->name('stats.applicants');
    Route::get('/stats/waves', [ApiController::class, 'waveStats'])->name('stats.waves');
    Route::get('/stats/payments', [ApiController::class, 'paymentStats'])->name('stats.payments');
    
    // Wave Management (AJAX)
    Route::get('/waves/active', [ApiController::class, 'activeWaves'])->name('waves.active');
    Route::get('/waves/{wave}/quota', [ApiController::class, 'waveQuota'])->name('waves.quota');
    Route::post('/waves/{wave}/check-quota', [ApiController::class, 'checkWaveQuota'])->name('waves.check-quota');
    
    // Applicant Validation (AJAX)
    Route::post('/validate/registration-number', [ApiController::class, 'validateRegistrationNumber'])->name('validate.registration-number');
    Route::post('/validate/email', [ApiController::class, 'validateEmail'])->name('validate.email');
    Route::post('/validate/phone', [ApiController::class, 'validatePhone'])->name('validate.phone');
    Route::post('/validate/nik', [ApiController::class, 'validateNIK'])->name('validate.nik');
    
    // File Upload (AJAX)
    Route::post('/upload/document', [ApiController::class, 'uploadDocument'])->name('upload.document');
    Route::post('/upload/photo', [ApiController::class, 'uploadPhoto'])->name('upload.photo');
    Route::delete('/upload/{file}', [ApiController::class, 'deleteFile'])->name('delete.file');
    
    // Search & Filter (AJAX)
    Route::get('/search/applicants', [ApiController::class, 'searchApplicants'])->name('search.applicants');
    Route::get('/search/waves', [ApiController::class, 'searchWaves'])->name('search.waves');
    Route::get('/filter/applicants', [ApiController::class, 'filterApplicants'])->name('filter.applicants');
    
    // Bulk Actions (AJAX)
    Route::post('/bulk/approve', [ApiController::class, 'bulkApprove'])->name('bulk.approve');
    Route::post('/bulk/reject', [ApiController::class, 'bulkReject'])->name('bulk.reject');
    Route::post('/bulk/verify-payment', [ApiController::class, 'bulkVerifyPayment'])->name('bulk.verify-payment');
    Route::post('/bulk/delete', [ApiController::class, 'bulkDelete'])->name('bulk.delete');
    
    // Notifications (AJAX)
    Route::post('/notify/applicants', [ApiController::class, 'notifyApplicants'])->name('notify.applicants');
    Route::post('/notify/single/{applicant}', [ApiController::class, 'notifyApplicant'])->name('notify.applicant');
    
    // Data Export (AJAX)
    Route::get('/export/preview', [ApiController::class, 'exportPreview'])->name('export.preview');
    Route::post('/export/start', [ApiController::class, 'startExport'])->name('export.start');
    Route::get('/export/status/{jobId}', [ApiController::class, 'exportStatus'])->name('export.status');
    Route::get('/export/download/{jobId}', [ApiController::class, 'downloadExport'])->name('export.download');
});
