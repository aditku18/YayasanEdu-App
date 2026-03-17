<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FoundationRegistrationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Platform\FoundationController;
use App\Http\Controllers\Platform\EmailVerificationController;
use App\Http\Controllers\Platform\ApproveYayasanController;
use App\Http\Controllers\Platform\SchoolController;
use App\Http\Controllers\Platform\StudentController;
use App\Http\Controllers\Platform\PlanController;
use App\Http\Controllers\Platform\InvoiceController;
use App\Http\Controllers\Platform\UserController;
use App\Http\Controllers\Platform\SettingController;
use App\Http\Controllers\Platform\DashboardController;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains', ['127.0.0.1', 'localhost']) as $domain) {
    if ($domain === 'localhost') {
        Route::domain($domain)->group(function () {
            Route::get('/', [LandingController::class, 'index'])->name('landing');

        Route::get('/register-foundation', [FoundationRegistrationController::class, 'showForm'])->name('register.foundation');
        Route::post('/register-foundation', [FoundationRegistrationController::class, 'register'])->name('foundation.register');
        Route::get('/registration-success', [FoundationRegistrationController::class, 'success'])->name('registration.success');

        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

        Route::get('/trial-expired', function () {
            return view('trial-expired');
        })->name('trial.expired');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // Platform Admin Routes (Dilindungi oleh check role platform_admin)
            Route::middleware([\App\Http\Middleware\PlatformAdminMiddleware::class])->prefix('platform')->name('platform.')->group(function () {
                // Dashboard shortcut for platform area
                Route::get('/', function () {
                    return redirect()->route('platform.foundations.index');
                });
                Route::get('/dashboard', [DashboardController::class, 'index'])->name('platform.dashboard');
                Route::get('/foundations', [FoundationController::class, 'index'])->name('foundations.index');
                Route::get('/foundations/{foundation}', [FoundationController::class, 'show'])->name('foundations.show');
                Route::post('/foundations/{foundation}/approve', [ApproveYayasanController::class, 'approve'])->name('foundations.approve');
                Route::post('/foundations/{foundation}/reject', [FoundationController::class, 'reject'])->name('foundations.reject');
                Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
                Route::get('/students', [StudentController::class, 'index'])->name('students.index');
                Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
                Route::get('/plans/create', [PlanController::class, 'create'])->name('plans.create');
                Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
                Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
                Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
                Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
                Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
                Route::get('/users', [UserController::class, 'index'])->name('users.index');
                Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
                Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

                // Verifikasi Email Yayasan
                Route::get('/email-verifications', [EmailVerificationController::class, 'index'])->name('email-verifications.index');
            });

            require __DIR__.'/auth.php';
        });
    } else {
        Route::domain($domain)->group(function () {
            Route::get('/register-foundation', [FoundationRegistrationController::class, 'showForm'])->name('register.foundation');
            Route::post('/register-foundation', [FoundationRegistrationController::class, 'register'])->name('foundation.register');
            Route::get('/registration-success', [FoundationRegistrationController::class, 'success'])->name('registration.success');

            Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

            Route::get('/trial-expired', function () {
                return view('trial-expired');
            })->name('trial.expired');

            Route::middleware('auth')->group(function () {
                Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
                Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

                // Platform Admin Routes (Dilindungi oleh check role platform_admin)
                Route::middleware([\App\Http\Middleware\PlatformAdminMiddleware::class])->prefix('platform')->name('platform.')->group(function () {
                    // Dashboard shortcut for platform area
                    Route::get('/', function () {
                        return redirect()->route('platform.foundations.index');
                    });
                    Route::get('/dashboard', [DashboardController::class, 'index'])->name('platform.dashboard');
                    Route::get('/foundations', [FoundationController::class, 'index'])->name('foundations.index');
                    Route::get('/foundations/{foundation}', [FoundationController::class, 'show'])->name('foundations.show');
                    Route::post('/foundations/{foundation}/approve', [ApproveYayasanController::class, 'approve'])->name('foundations.approve');
                    Route::post('/foundations/{foundation}/reject', [FoundationController::class, 'reject'])->name('foundations.reject');
                    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
                    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
                    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
                    Route::get('/plans/create', [PlanController::class, 'create'])->name('plans.create');
                    Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
                    Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
                    Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
                    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
                    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
                    Route::get('/users', [UserController::class, 'index'])->name('users.index');
                    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
                    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

                    // Verifikasi Email Yayasan
                    Route::get('/email-verifications', [EmailVerificationController::class, 'index'])->name('email-verifications.index');
                    Route::post('/email-verifications/{user}/verify', [EmailVerificationController::class, 'verify'])->name('email-verifications.verify');
                    Route::post('/email-verifications/{user}/resend', [EmailVerificationController::class, 'resend'])->name('email-verifications.resend');
                });
            });

            require __DIR__.'/auth.php';
        });
    }
}
