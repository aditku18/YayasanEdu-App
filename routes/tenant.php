<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\WizardController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Middleware\CheckTrialStatus;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Hanya untuk domain tenant (subdomain.localhost). Domain sentral (127.0.0.1,
| localhost) menampilkan landing dari routes/web.php.
|
*/

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // PPDB Public Portal
    Route::get('/pendaftaran', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'index'])->name('tenant.ppdb.public.index');
    Route::get('/ppdb/daftar/{wave}', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'register'])->name('tenant.ppdb.public.register');
    Route::post('/ppdb/daftar', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'store'])->name('tenant.ppdb.public.store');
    Route::get('/ppdb/success/{id}', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'success'])->name('tenant.ppdb.public.success');
    
    // NEW: Tracking & Upload
    Route::get('/ppdb/cek-status', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'checkStatus'])->name('tenant.ppdb.public.check-status');
    Route::post('/ppdb/cek-status', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'tracking'])->name('tenant.ppdb.public.tracking');
    Route::get('/ppdb/upload/{reg_number}', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'upload'])->name('tenant.ppdb.public.upload');
    Route::post('/ppdb/upload', [\App\Http\Controllers\Tenant\PPDBPublicController::class, 'storeDocuments'])->name('tenant.ppdb.public.store-docs');

    // Trial expired page (tanpa CheckTrialStatus agar bisa diakses)
    Route::get('/trial-expired', function () {
        return view('trial-expired');
    })->middleware(['auth'])->name('tenant.trial-expired');

    Route::middleware(['auth', 'tenant.verified', CheckTrialStatus::class, 'school.status'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('tenant.dashboard-redirect');
        });
        
        // Legacy routes for backward compatibility - MUST come before dynamic {school} routes
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        
        // Redirect /school/dashboard to the user's school slug
        Route::get('/school/dashboard', function () {
            $user = auth()->user();
            
            if (!$user) {
                return redirect('/login');
            }
            
            // Get the school for the user
            $school = null;
            
            if ($user->hasRole('school_admin') || $user->hasRole('staff')) {
                $school = \App\Models\SchoolUnit::find($user->school_unit_id);
            } elseif ($user->hasRole('teacher')) {
                $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
                $school = \App\Models\SchoolUnit::find($teacher?->school_id);
            }
            
            if ($school && $school->slug) {
                return redirect()->to('/' . $school->slug . '/dashboard');
            }
            
            // Fallback to regular dashboard
            return redirect()->route('tenant.dashboard');
        })->name('tenant.school.dashboard');
        
        Route::get('/teacher/dashboard', [DashboardController::class, 'index'])->name('tenant.teacher.dashboard');
        Route::get('/yayasan/dashboard', [DashboardController::class, 'index'])->name('tenant.yayasan.dashboard');
        
        // Dynamic dashboard based on school slug - Unified route for all roles
        // Usage: /{slug}/dashboard e.g., /sma-kemala/dashboard
        // This MUST come after legacy routes to avoid catching /school/dashboard
        Route::get('/{school}/dashboard', [DashboardController::class, 'index']);
        
        // Role-based redirect to school-specific dashboard
        Route::get('/user/dashboard-redirect', function () {
            $user = auth()->user();
            
            // Get the school slug for the user
            $schoolSlug = null;
            
            if ($user->hasRole('school_admin') || $user->hasRole('staff')) {
                // School admin/staff - use their assigned school
                $school = \App\Models\SchoolUnit::find($user->school_unit_id);
                $schoolSlug = $school?->slug;
            } elseif ($user->hasRole('teacher')) {
                // Teacher - use the school they're assigned to
                $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
                $school = \App\Models\SchoolUnit::find($teacher?->school_id);
                $schoolSlug = $school?->slug;
            }
            
            if ($schoolSlug) {
                return redirect()->route('tenant.school.dashboard', ['school' => $schoolSlug]);
            }
            
            // Foundation admin or no school assigned - redirect to yayasan profile (as index)
            return redirect()->route('tenant.yayasan.profil');
        })->name('tenant.dashboard-redirect');
        
        // ============================================
        // Workspace & Utility Routes (Standard Prefixes)
        // These MUST come before the dynamic {school} slug group
        // ============================================

        Route::get('/setup-wizard', [WizardController::class, 'index'])->name('tenant.wizard');
        Route::post('/setup-wizard', [WizardController::class, 'store'])->name('tenant.wizard.store');

        Route::get('/select-unit/{school}', [\App\Http\Controllers\Tenant\UnitSelectionController::class, 'select'])->name('tenant.select-unit');
        
        // Subscription Management Routes
        Route::group(['prefix' => 'subscription'], function () {
            Route::get('/current', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'current'])->name('tenant.subscription.current');
            Route::get('/upgrade', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'upgrade'])->name('tenant.plan.upgrade');
            Route::get('/history', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'history'])->name('tenant.payment.history');
        });

        // Add-on Management
        Route::resource('addon', \App\Http\Controllers\Tenant\AddonController::class)->names('tenant.addon');

        // Invoice Management
        Route::resource('invoice', \App\Http\Controllers\Tenant\InvoiceController::class)->names('tenant.invoice');
        
        // Invoice Payment Routes
        Route::get('/invoice/{invoice}/pay', [\App\Http\Controllers\Tenant\InvoiceController::class, 'pay'])->name('tenant.invoice.pay');
        Route::post('/invoice/{invoice}/process-payment', [\App\Http\Controllers\Tenant\InvoiceController::class, 'processPayment'])->name('tenant.invoice.process-payment');
        Route::post('/invoice/{invoice}/upload-receipt', [\App\Http\Controllers\Tenant\InvoiceController::class, 'uploadReceipt'])->name('tenant.invoice.upload-receipt');
        Route::post('/invoice/{invoice}/verify', [\App\Http\Controllers\Tenant\InvoiceController::class, 'verifyPayment'])->name('tenant.invoice.verify');
        
        // Payment Callback Routes
        Route::get('/payment/callback/{gateway}/{reference}', [\App\Http\Controllers\Tenant\InvoiceController::class, 'paymentCallback'])->name('tenant.invoice.payment.callback');
        
        // Marketplace & Plugin Management
        Route::group(['prefix' => 'marketplace'], function () {
            Route::get('/', [\App\Http\Controllers\Tenant\MarketplaceController::class, 'index'])->name('tenant.marketplace.index');
            Route::get('/{plugin}', [\App\Http\Controllers\Tenant\MarketplaceController::class, 'show'])->name('tenant.marketplace.show');
            Route::post('/{plugin}/purchase', [\App\Http\Controllers\Tenant\MarketplaceController::class, 'purchase'])->name('tenant.marketplace.purchase');
            Route::post('/{plugin}/uninstall', [\App\Http\Controllers\Tenant\MarketplaceController::class, 'uninstall'])->name('tenant.marketplace.uninstall');
            Route::post('/install-after-payment/{invoice}', [\App\Http\Controllers\Tenant\MarketplaceController::class, 'installAfterPayment'])->name('tenant.marketplace.install-after-payment');
        });

        Route::group(['prefix' => 'plugin'], function () {
            Route::get('/', [\App\Http\Controllers\Tenant\PluginController::class, 'index'])->name('tenant.plugins.index');
            Route::get('/active', [\App\Http\Controllers\Tenant\PluginController::class, 'active'])->name('tenant.plugin.active');
            Route::get('/installed', [\App\Http\Controllers\Tenant\PluginController::class, 'installed'])->name('tenant.plugin.installed');
            Route::get('/purchase', [\App\Http\Controllers\Tenant\PluginController::class, 'purchase'])->name('tenant.plugin.purchase');
            Route::get('/{plugin}', [\App\Http\Controllers\Tenant\PluginController::class, 'show'])->name('tenant.plugin.show');
            Route::post('/{plugin}/install', [\App\Http\Controllers\Tenant\PluginController::class, 'install'])->name('tenant.plugin.install');
            Route::post('/{plugin}/activate', [\App\Http\Controllers\Tenant\PluginController::class, 'activate'])->name('tenant.plugin.activate');
            Route::post('/{plugin}/deactivate', [\App\Http\Controllers\Tenant\PluginController::class, 'deactivate'])->name('tenant.plugin.deactivate');
            Route::post('/{plugin}/settings', [\App\Http\Controllers\Tenant\PluginController::class, 'updateSettings'])->name('tenant.plugin.settings.update');
            Route::delete('/{plugin}/uninstall', [\App\Http\Controllers\Tenant\PluginController::class, 'uninstall'])->name('tenant.plugin.uninstall');
        });
        
        // User Management Routes
        Route::group(['prefix' => 'user'], function () {
            Route::get('/admin-yayasan', [\App\Http\Controllers\Tenant\UserManagementController::class, 'adminYayasan'])->name('tenant.user.admin-yayasan');
            Route::get('/admin-sekolah', [\App\Http\Controllers\Tenant\UserManagementController::class, 'adminSekolah'])->name('tenant.user.admin-sekolah');
        });

        // Role Management Routes
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [\App\Http\Controllers\Tenant\UserManagementController::class, 'roles'])->name('tenant.roles.index');
            Route::post('/', [\App\Http\Controllers\Tenant\UserManagementController::class, 'storeRole'])->name('tenant.roles.store');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\UserManagementController::class, 'updateRole'])->name('tenant.roles.update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\UserManagementController::class, 'deleteRole'])->name('tenant.roles.destroy');
            Route::get('/{id}/permissions', [\App\Http\Controllers\Tenant\UserManagementController::class, 'getRolePermissions'])->name('tenant.roles.permissions');
        });

        // Singular role route for compatibility
        Route::get('/role', function () {
            return redirect()->route('tenant.roles.index');
        })->name('tenant.role');

        // Activity Log
        Route::get('/activity', [\App\Http\Controllers\Tenant\UserManagementController::class, 'activityLog'])->name('tenant.activity.log');
        Route::post('/activity/export', [\App\Http\Controllers\Tenant\UserManagementController::class, 'exportActivityLog'])->name('tenant.activity.export');
        Route::post('/activity/clear', [\App\Http\Controllers\Tenant\UserManagementController::class, 'clearActivityLog'])->name('tenant.activity.clear');
        
        // Integration Routes
        Route::group(['prefix' => 'integration'], function () {
            Route::get('/api', [\App\Http\Controllers\Tenant\IntegrationController::class, 'api'])->name('tenant.integration.api');
            Route::get('/whatsapp', [\App\Http\Controllers\Tenant\IntegrationController::class, 'whatsapp'])->name('tenant.integration.whatsapp');
            Route::get('/absensi', [\App\Http\Controllers\Tenant\IntegrationController::class, 'absensi'])->name('tenant.integration.absensi');
            Route::get('/google', [\App\Http\Controllers\Tenant\IntegrationController::class, 'google'])->name('tenant.integration.google');
            Route::get('/payment', [\App\Http\Controllers\Tenant\IntegrationController::class, 'payment'])->name('tenant.integration.payment');
        });
        
        // Finance Routes
        Route::group(['prefix' => 'finance'], function () {
            Route::get('/report', [\App\Http\Controllers\Tenant\FinanceController::class, 'report'])->name('tenant.finance.report');
        });

        Route::resource('bill', \App\Http\Controllers\Tenant\BillController::class)->names('tenant.bill');
        Route::resource('payment', \App\Http\Controllers\Tenant\PaymentController::class)->names('tenant.payment');
        
        // Analytics Routes
        Route::group(['prefix' => 'analytics'], function () {
            Route::get('/usage', [\App\Http\Controllers\Tenant\AnalyticsController::class, 'usage'])->name('tenant.analytics.usage');
        });

        // Report Routes
        Route::group(['prefix' => 'report'], function () {
            Route::get('/school', [\App\Http\Controllers\Tenant\ReportController::class, 'school'])->name('tenant.report.school');
            Route::get('/system', [\App\Http\Controllers\Tenant\ReportController::class, 'system'])->name('tenant.report.system');
        });
        
        // Support Routes
        Route::resource('ticket', \App\Http\Controllers\Tenant\SupportController::class)->names('tenant.support');
        Route::resource('documentation', \App\Http\Controllers\Tenant\DocumentationController::class)->names('tenant.documentation');
        Route::get('/documentation/category/{category}', [\App\Http\Controllers\Tenant\DocumentationController::class, 'show'])->name('tenant.documentation.category');
        Route::get('/documentation/category/{category}/{article}', [\App\Http\Controllers\Tenant\DocumentationController::class, 'show'])->name('tenant.documentation.article');
        Route::get('/documentation/search', [\App\Http\Controllers\Tenant\DocumentationController::class, 'search'])->name('tenant.documentation.search');
        Route::resource('contact', \App\Http\Controllers\Tenant\ContactController::class)->names('tenant.contact');
        
        // Settings Routes
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/notification', [\App\Http\Controllers\Tenant\SettingController::class, 'notification'])->name('tenant.setting.notification');
            Route::post('/notification', [\App\Http\Controllers\Tenant\SettingController::class, 'updateNotification'])->name('tenant.setting.notification.update');
            Route::get('/security', [\App\Http\Controllers\Tenant\SettingController::class, 'security'])->name('tenant.setting.security');
            Route::post('/security', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSecurity'])->name('tenant.setting.security.update');
            Route::get('/backup', [\App\Http\Controllers\Tenant\SettingController::class, 'backup'])->name('tenant.setting.backup');
            Route::post('/backup', [\App\Http\Controllers\Tenant\SettingController::class, 'updateBackup'])->name('tenant.setting.backup.update');
            Route::post('/backup/create', [\App\Http\Controllers\Tenant\SettingController::class, 'createBackup'])->name('tenant.setting.backup.create');
        });

        // Audit Log
        Route::resource('audit', \App\Http\Controllers\Tenant\AuditController::class)->names('tenant.audit');
        Route::get('/audit/export', [\App\Http\Controllers\Tenant\AuditController::class, 'export'])->name('tenant.audit.export');
        
        // Enterprise Yayasan Routes - Consolidated
        Route::group(['prefix' => 'yayasan'], function () {
            Route::get('/', [\App\Http\Controllers\Tenant\FoundationController::class, 'index'])->name('tenant.yayasan.profil');
            Route::get('/profil', [\App\Http\Controllers\Tenant\FoundationController::class, 'index']);
            Route::post('/profil', [\App\Http\Controllers\Tenant\FoundationController::class, 'update'])->name('tenant.yayasan.profil.update');
            Route::post('/sejarah', [\App\Http\Controllers\Tenant\FoundationController::class, 'updateSejarah'])->name('tenant.yayasan.sejarah.update');
            Route::post('/legalitas', [\App\Http\Controllers\Tenant\FoundationController::class, 'updateLegalitas'])->name('tenant.yayasan.legalitas.update');
            Route::get('/legalitas', [\App\Http\Controllers\Tenant\FoundationController::class, 'legalitas'])->name('tenant.yayasan.legalitas');
            Route::get('/struktur', [\App\Http\Controllers\Tenant\FoundationController::class, 'struktur'])->name('tenant.yayasan.struktur');
            Route::get('/branding', [\App\Http\Controllers\Tenant\FoundationController::class, 'branding'])->name('tenant.yayasan.branding');
            Route::post('/branding', [\App\Http\Controllers\Tenant\FoundationController::class, 'updateBranding'])->name('tenant.yayasan.branding.update');
            Route::get('/domain', [\App\Http\Controllers\Tenant\FoundationController::class, 'domain'])->name('tenant.yayasan.domain');
            Route::post('/domain', [\App\Http\Controllers\Tenant\FoundationController::class, 'updateDomain'])->name('tenant.yayasan.domain.update');
            Route::put('/units/{school}', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'update'])->name('tenant.units.update');
            Route::post('/units/{school}/activate', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'activate'])->name('tenant.units.activate');
            Route::post('/units/{school}/deactivate', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'deactivate'])->name('tenant.units.deactivate');
            
            Route::get('/units', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'index'])->name('tenant.units.index');
            Route::get('/units/create', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'create'])->name('tenant.units.create');
            Route::post('/units', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'store'])->name('tenant.units.store');
            Route::get('/units/{school}/edit', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'edit'])->name('tenant.units.edit');
            
            Route::get('/units/{school}/status', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'status'])->name('tenant.school.status');
            Route::get('/units/{school}/settings', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'settings'])->name('tenant.school.settings');
            
            Route::get('/teachers', [\App\Http\Controllers\Tenant\TeacherController::class, 'index'])->name('tenant.teachers.index');
            Route::get('/teachers/create', [\App\Http\Controllers\Tenant\TeacherController::class, 'create'])->name('tenant.teachers.create');
            Route::post('/teachers', [\App\Http\Controllers\Tenant\TeacherController::class, 'store'])->name('tenant.teachers.store');
            Route::post('/teachers/{teacher}/placement', [\App\Http\Controllers\Tenant\TeacherController::class, 'updatePlacement'])->name('tenant.teachers.placement');
            
            Route::get('/students', [\App\Http\Controllers\Tenant\StudentController::class, 'index'])->name('tenant.students.index');
            Route::get('/students/template', [\App\Http\Controllers\Tenant\StudentController::class, 'downloadTemplate'])->name('tenant.students.template');
            Route::post('/students/import', [\App\Http\Controllers\Tenant\StudentController::class, 'import'])->name('tenant.students.import');
            Route::get('/students/create', [\App\Http\Controllers\Tenant\StudentController::class, 'create'])->name('tenant.students.create');
            Route::post('/students', [\App\Http\Controllers\Tenant\StudentController::class, 'store'])->name('tenant.students.store');
            Route::get('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'show'])->name('tenant.students.show');
            Route::get('/students/{student}/edit', [\App\Http\Controllers\Tenant\StudentController::class, 'edit'])->name('tenant.students.edit');
            Route::put('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'update'])->name('tenant.students.update');
            Route::delete('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'destroy'])->name('tenant.students.destroy');
            
            Route::get('/staff', [\App\Http\Controllers\Tenant\StaffController::class, 'index'])->name('tenant.staff.index');
            Route::get('/staff/create', [\App\Http\Controllers\Tenant\StaffController::class, 'create'])->name('tenant.staff.create');
            Route::post('/staff', [\App\Http\Controllers\Tenant\StaffController::class, 'store'])->name('tenant.staff.store');
        });

        // ============================================
        // School-Specific Routes (Dynamic Slug)
        // Usage: /{slug}/dashboard, /{slug}/students, etc.
        // ============================================
        Route::middleware(['school.slug'])->group(function () {
            Route::group(['prefix' => '{school}'], function () {
                // Dashboard already defined above, adding more school-specific routes
                
                // Students (school-specific)
                Route::get('/students', [\App\Http\Controllers\Tenant\StudentController::class, 'index'])->name('tenant.school.students.index');
                Route::get('/students/create', [\App\Http\Controllers\Tenant\StudentController::class, 'create'])->name('tenant.school.students.create');
                Route::post('/students', [\App\Http\Controllers\Tenant\StudentController::class, 'store'])->name('tenant.school.students.store');
                Route::post('/students/import', [\App\Http\Controllers\Tenant\StudentController::class, 'import'])->name('tenant.school.students.import');
                Route::get('/students/template', [\App\Http\Controllers\Tenant\StudentController::class, 'downloadTemplate'])->name('tenant.school.students.template');
                Route::get('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'show'])->name('tenant.school.students.show');
                Route::get('/students/{student}/edit', [\App\Http\Controllers\Tenant\StudentController::class, 'edit'])->name('tenant.school.students.edit');
                Route::put('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'update'])->name('tenant.school.students.update');
                Route::delete('/students/{student}', [\App\Http\Controllers\Tenant\StudentController::class, 'destroy'])->name('tenant.school.students.destroy');
                
                // Teachers (school-specific)
                Route::get('/teachers', [\App\Http\Controllers\Tenant\TeacherController::class, 'index'])->name('tenant.school.teachers.index');
                Route::get('/teachers/create', [\App\Http\Controllers\Tenant\TeacherController::class, 'create'])->name('tenant.school.teachers.create');
                Route::post('/teachers', [\App\Http\Controllers\Tenant\TeacherController::class, 'store'])->name('tenant.school.teachers.store');
                Route::get('/teachers/{teacher}', [\App\Http\Controllers\Tenant\TeacherController::class, 'show'])->name('tenant.school.teachers.show');
                Route::get('/teachers/{teacher}/edit', [\App\Http\Controllers\Tenant\TeacherController::class, 'edit'])->name('tenant.school.teachers.edit');
                Route::put('/teachers/{teacher}', [\App\Http\Controllers\Tenant\TeacherController::class, 'update'])->name('tenant.school.teachers.update');
                Route::delete('/teachers/{teacher}', [\App\Http\Controllers\Tenant\TeacherController::class, 'destroy'])->name('tenant.school.teachers.destroy');
                Route::post('/teachers/{teacher}/placement', [\App\Http\Controllers\Tenant\TeacherController::class, 'updatePlacement'])->name('tenant.school.teachers.placement');
                
                // Classrooms (school-specific)
                Route::resource('classrooms', \App\Http\Controllers\Tenant\ClassRoomController::class)->names('tenant.school.classrooms');
                
                // Finance (school-specific)
                Route::get('/finance', [\App\Http\Controllers\Tenant\FinanceController::class, 'index'])->name('tenant.school.finance.index');
                Route::get('/finance/tagihan', [\App\Http\Controllers\Tenant\FinanceController::class, 'tagihan'])->name('tenant.school.finance.tagihan');

                // Bill Types
                Route::get('/finance/bill-types', [\App\Http\Controllers\Tenant\FinanceController::class, 'billTypes'])->name('tenant.school.finance.bill-types.index');
                Route::get('/finance/bill-types/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createBillType'])->name('tenant.school.finance.bill-types.create');
                Route::post('/finance/bill-types', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeBillType'])->name('tenant.school.finance.bill-types.store');
                Route::get('/finance/bill-types/{billType}/edit', [\App\Http\Controllers\Tenant\FinanceController::class, 'editBillType'])->name('tenant.school.finance.bill-types.edit');
                Route::put('/finance/bill-types/{billType}', [\App\Http\Controllers\Tenant\FinanceController::class, 'updateBillType'])->name('tenant.school.finance.bill-types.update');
                Route::delete('/finance/bill-types/{billType}', [\App\Http\Controllers\Tenant\FinanceController::class, 'destroyBillType'])->name('tenant.school.finance.bill-types.destroy');

                // Invoices
                Route::get('/finance/invoices', [\App\Http\Controllers\Tenant\FinanceController::class, 'invoices'])->name('tenant.school.finance.invoices.index');
                Route::get('/finance/invoices/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createInvoice'])->name('tenant.school.finance.invoices.create');
                Route::post('/finance/invoices', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeInvoice'])->name('tenant.school.finance.invoices.store');
                Route::post('/finance/invoices/generate', [\App\Http\Controllers\Tenant\FinanceController::class, 'generateInvoices'])->name('tenant.school.finance.invoices.generate');
                Route::get('/finance/invoices/{invoice}', [\App\Http\Controllers\Tenant\FinanceController::class, 'showInvoice'])->name('tenant.school.finance.invoices.show');
                Route::get('/finance/invoices/{invoice}/edit', [\App\Http\Controllers\Tenant\FinanceController::class, 'editInvoice'])->name('tenant.school.finance.invoices.edit');
                Route::put('/finance/invoices/{invoice}', [\App\Http\Controllers\Tenant\FinanceController::class, 'updateInvoice'])->name('tenant.school.finance.invoices.update');
                Route::delete('/finance/invoices/{invoice}', [\App\Http\Controllers\Tenant\FinanceController::class, 'destroyInvoice'])->name('tenant.school.finance.invoices.destroy');

                // Payments
                Route::get('/finance/payments', [\App\Http\Controllers\Tenant\FinanceController::class, 'payments'])->name('tenant.school.finance.payments.index');
                Route::get('/finance/payments/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createPayment'])->name('tenant.school.finance.payments.create');
                Route::post('/finance/payments', [\App\Http\Controllers\Tenant\FinanceController::class, 'storePayment'])->name('tenant.school.finance.payments.store');
                Route::get('/finance/payments/{payment}', [\App\Http\Controllers\Tenant\FinanceController::class, 'showPayment'])->name('tenant.school.finance.payments.show');
                Route::post('/finance/payments/{payment}/confirm', [\App\Http\Controllers\Tenant\FinanceController::class, 'confirmPayment'])->name('tenant.school.finance.payments.confirm');
                Route::post('/finance/payments/{payment}/reject', [\App\Http\Controllers\Tenant\FinanceController::class, 'rejectPayment'])->name('tenant.school.finance.payments.reject');

                // SPP Payment
                Route::get('/finance/spp/payment', [\App\Http\Controllers\Tenant\FinanceController::class, 'sppPayment'])->name('tenant.school.finance.spp.payment');
                Route::post('/finance/spp/process', [\App\Http\Controllers\Tenant\FinanceController::class, 'processSppPayment'])->name('tenant.school.finance.spp.process');

                // Expense Categories
                Route::get('/finance/expense-categories', [\App\Http\Controllers\Tenant\FinanceController::class, 'expenseCategories'])->name('tenant.school.finance.expense-categories.index');
                Route::get('/finance/expense-categories/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createExpenseCategory'])->name('tenant.school.finance.expense-categories.create');
                Route::post('/finance/expense-categories', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeExpenseCategory'])->name('tenant.school.finance.expense-categories.store');

                // Expenses
                Route::get('/finance/expenses', [\App\Http\Controllers\Tenant\FinanceController::class, 'expenses'])->name('tenant.school.finance.expenses.index');
                Route::get('/finance/expenses/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createExpense'])->name('tenant.school.finance.expenses.create');
                Route::post('/finance/expenses', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeExpense'])->name('tenant.school.finance.expenses.store');
                Route::get('/finance/expenses/{expense}', [\App\Http\Controllers\Tenant\FinanceController::class, 'showExpense'])->name('tenant.school.finance.expenses.show');
                Route::post('/finance/expenses/{expense}/approve', [\App\Http\Controllers\Tenant\FinanceController::class, 'approveExpense'])->name('tenant.school.finance.expenses.approve');
                Route::post('/finance/expenses/{expense}/reject', [\App\Http\Controllers\Tenant\FinanceController::class, 'rejectExpense'])->name('tenant.school.finance.expenses.reject');

                // Cash Transactions
                Route::get('/finance/cash', [\App\Http\Controllers\Tenant\FinanceController::class, 'cashTransactions'])->name('tenant.school.finance.cash.index');
                Route::get('/finance/cash/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createCashTransaction'])->name('tenant.school.finance.cash.create');
                Route::post('/finance/cash', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeCashTransaction'])->name('tenant.school.finance.cash.store');

                // Reports
                Route::get('/finance/reports', [\App\Http\Controllers\Tenant\FinanceController::class, 'reports'])->name('tenant.school.finance.reports.index');
                Route::get('/finance/reports/print', [\App\Http\Controllers\Tenant\FinanceController::class, 'printReport'])->name('tenant.school.finance.reports.print');

                // Receivables
                Route::get('/finance/receivables', [\App\Http\Controllers\Tenant\FinanceController::class, 'receivables'])->name('tenant.school.finance.receivables.index');

                // Installments
                Route::get('/finance/installments', [\App\Http\Controllers\Tenant\FinanceController::class, 'installmentPlans'])->name('tenant.school.finance.installments.index');
                Route::get('/finance/installments/create', [\App\Http\Controllers\Tenant\FinanceController::class, 'createInstallmentPlan'])->name('tenant.school.finance.installments.create');
                Route::post('/finance/installments', [\App\Http\Controllers\Tenant\FinanceController::class, 'storeInstallmentPlan'])->name('tenant.school.finance.installments.store');
                
                // Yayasan Profile (school-specific) - Showing School Unit Profile
                Route::get('/profil', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'profile'])->name('tenant.school.profile');
                Route::post('/profil', [\App\Http\Controllers\Tenant\SchoolUnitController::class, 'updateProfile'])->name('tenant.school.profile.update');
                
                Route::get('/yayasan/profil', [\App\Http\Controllers\Tenant\FoundationController::class, 'index'])->name('tenant.school.yayasan.profil');
                Route::get('/legalitas', [\App\Http\Controllers\Tenant\FoundationController::class, 'legalitas'])->name('tenant.school.yayasan.legalitas');
                Route::get('/struktur', [\App\Http\Controllers\Tenant\FoundationController::class, 'struktur'])->name('tenant.school.yayasan.struktur');
                
                // Attendance (school-specific)
                Route::get('/attendance', [\App\Http\Controllers\Tenant\AttendanceController::class, 'index'])->name('tenant.school.attendance.index');
                Route::get('/attendance/create/{classroom}', [\App\Http\Controllers\Tenant\AttendanceController::class, 'create'])->name('tenant.school.attendance.create');
                Route::post('/attendance', [\App\Http\Controllers\Tenant\AttendanceController::class, 'store'])->name('tenant.school.attendance.store');
                
                // Grades / Penilaian (school-specific)
                Route::get('/penilaian', [\App\Http\Controllers\Tenant\GradeController::class, 'index'])->name('tenant.school.grades.index');
                Route::get('/penilaian/komponen/create', [\App\Http\Controllers\Tenant\GradeController::class, 'createComponent'])->name('tenant.school.grades.create-component');
                Route::post('/penilaian/komponen', [\App\Http\Controllers\Tenant\GradeController::class, 'storeComponent'])->name('tenant.school.grades.store-component');
                Route::get('/penilaian/komponen/{gradeComponent}/input', [\App\Http\Controllers\Tenant\GradeController::class, 'inputGrades'])->name('tenant.school.grades.input');
                Route::post('/penilaian/komponen/{gradeComponent}', [\App\Http\Controllers\Tenant\GradeController::class, 'storeGrades'])->name('tenant.school.grades.store');
                Route::get('/penilaian/raport', [\App\Http\Controllers\Tenant\GradeController::class, 'raport'])->name('tenant.school.grades.raport');
                Route::get('/penilaian/siswa/{student}', [\App\Http\Controllers\Tenant\GradeController::class, 'studentGrades'])->name('tenant.school.grades.student');
                Route::delete('/penilaian/komponen/{gradeComponent}', [\App\Http\Controllers\Tenant\GradeController::class, 'destroyComponent'])->name('tenant.school.grades.destroy-component');
                
                // Rekap Nilai
                Route::get('/penilaian/rekap', [\App\Http\Controllers\Tenant\GradeController::class, 'rekapNilai'])->name('tenant.school.grades.rekap');
                
                // Analisis Hasil Belajar
                Route::get('/penilaian/analisis', [\App\Http\Controllers\Tenant\GradeController::class, 'analisis'])->name('tenant.school.grades.analisis');
                
                // Export Raport
                Route::get('/penilaian/raport/{student}/export', [\App\Http\Controllers\Tenant\GradeController::class, 'exportRaport'])->name('tenant.school.grades.export-raport');
                
                // Import/Export Grades
                Route::get('/penilaian/import', [\App\Http\Controllers\Tenant\GradeController::class, 'importForm'])->name('tenant.school.grades.import-form');
                Route::post('/penilaian/import', [\App\Http\Controllers\Tenant\GradeController::class, 'importGrades'])->name('tenant.school.grades.import');
                Route::get('/penilaian/export', [\App\Http\Controllers\Tenant\GradeController::class, 'exportGrades'])->name('tenant.school.grades.export');
                
                // Penilaian Sikap
                Route::get('/penilaian/sikap', [\App\Http\Controllers\Tenant\BehaviorGradeController::class, 'index'])->name('tenant.school.grades.sikap.index');
                Route::post('/penilaian/sikap', [\App\Http\Controllers\Tenant\BehaviorGradeController::class, 'store'])->name('tenant.school.grades.sikap.store');
                
                // PPDB (school-specific)
                Route::group(['prefix' => 'ppdb'], function () {
                    Route::get('/', [\App\Http\Controllers\Tenant\PPDBController::class, 'dashboard'])->name('tenant.school.ppdb.index');
                    Route::get('/applicants', [\App\Http\Controllers\Tenant\PPDBController::class, 'applicants'])->name('tenant.school.ppdb.applicants');
                    Route::get('/applicants/{id}', [\App\Http\Controllers\Tenant\PPDBController::class, 'showApplicant'])->name('tenant.school.ppdb.applicants.show');
                    Route::post('/applicants/{id}/status', [\App\Http\Controllers\Tenant\PPDBController::class, 'updateApplicantStatus'])->name('tenant.school.ppdb.applicants.status');
                    Route::post('/applicants/{id}/verify-payment', [\App\Http\Controllers\Tenant\PPDBController::class, 'verifyPayment'])->name('tenant.school.ppdb.applicants.verify-payment');
                    Route::get('/settings', [\App\Http\Controllers\Tenant\PPDBController::class, 'settings'])->name('tenant.school.ppdb.settings');
                    Route::get('/waves/create', [\App\Http\Controllers\Tenant\PPDBController::class, 'createWave'])->name('tenant.school.ppdb.waves.create');
                    Route::post('/waves', [\App\Http\Controllers\Tenant\PPDBController::class, 'storeWave'])->name('tenant.school.ppdb.waves.store');
                    Route::get('/waves/{wave}/edit', [\App\Http\Controllers\Tenant\PPDBController::class, 'editWave'])->name('tenant.school.ppdb.waves.edit');
                    Route::put('/waves/{wave}', [\App\Http\Controllers\Tenant\PPDBController::class, 'updateWave'])->name('tenant.school.ppdb.waves.update');
                    Route::delete('/waves/{wave}', [\App\Http\Controllers\Tenant\PPDBController::class, 'destroyWave'])->name('tenant.school.ppdb.waves.destroy');
                    Route::post('/waves/{wave}/toggle-status', [\App\Http\Controllers\Tenant\PPDBController::class, 'toggleWaveStatus'])->name('tenant.school.ppdb.waves.toggle-status');
                    
                    // Fee Breakdown Routes
                    Route::post('/fee-components', [\App\Http\Controllers\Tenant\PPDBController::class, 'storeFeeComponent'])->name('tenant.school.ppdb.fee-components.store');
                    Route::delete('/fee-components/{id}', [\App\Http\Controllers\Tenant\PPDBController::class, 'destroyFeeComponent'])->name('tenant.school.ppdb.fee-components.destroy');
                    Route::get('/waves/{wave}/fees', [\App\Http\Controllers\Tenant\PPDBController::class, 'fees'])->name('tenant.school.ppdb.waves.fees');
                    Route::post('/waves/{wave}/fees', [\App\Http\Controllers\Tenant\PPDBController::class, 'updateFees'])->name('tenant.school.ppdb.waves.fees.update');
                    Route::post('/applicants/{id}/verify', [\App\Http\Controllers\Tenant\PPDBController::class, 'verifyApplicant'])->name('tenant.school.ppdb.applicants.verify');
                });
                
                // Staff (school-specific)
                Route::get('/staff', [\App\Http\Controllers\Tenant\StaffController::class, 'index'])->name('tenant.school.staff.index');
                Route::get('/staff/create', [\App\Http\Controllers\Tenant\StaffController::class, 'create'])->name('tenant.school.staff.create');
                Route::post('/staff', [\App\Http\Controllers\Tenant\StaffController::class, 'store'])->name('tenant.school.staff.store');
                
                // Subjects (school-specific)
                Route::get('/subjects', [\App\Http\Controllers\Tenant\AcademicController::class, 'subjectsIndex'])->name('tenant.school.subjects.index');
                Route::get('/subjects/create', [\App\Http\Controllers\Tenant\AcademicController::class, 'createSubject'])->name('tenant.school.subjects.create');
                Route::post('/subjects', [\App\Http\Controllers\Tenant\AcademicController::class, 'storeSubject'])->name('tenant.school.subjects.store');
                Route::get('/subjects/{subject}/edit', [\App\Http\Controllers\Tenant\AcademicController::class, 'editSubject'])->name('tenant.school.subjects.edit');
                Route::put('/subjects/{subject}', [\App\Http\Controllers\Tenant\AcademicController::class, 'updateSubject'])->name('tenant.school.subjects.update');
                Route::delete('/subjects/{subject}', [\App\Http\Controllers\Tenant\AcademicController::class, 'destroySubject'])->name('tenant.school.subjects.destroy');
                
                // Schedule (school-specific)
                Route::get('/schedule', [\App\Http\Controllers\Tenant\AcademicController::class, 'scheduleIndex'])->name('tenant.school.schedule.index');
                Route::get('/schedule/create', [\App\Http\Controllers\Tenant\AcademicController::class, 'createSchedule'])->name('tenant.school.schedule.create');
                Route::post('/schedule', [\App\Http\Controllers\Tenant\AcademicController::class, 'storeSchedule'])->name('tenant.school.schedule.store');
            });
        });
    });
    
    // Auth routes for tenant
    require __DIR__.'/tenant-auth.php';
});
