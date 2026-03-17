<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FoundationRegistrationController;
use App\Http\Controllers\FoundationRegistrationControllerNew;
use App\Http\Controllers\MultiStepRegistrationController;
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
use App\Http\Controllers\Platform\PaymentGatewayController;
use App\Http\Controllers\Platform\PaymentController;
use App\Http\Controllers\Platform\RecurringPaymentController;
use App\Http\Controllers\Platform\WebhookController;
use App\Http\Controllers\Platform\RegistrationController;
use App\Http\Controllers\Platform\SubscriptionController;
use App\Http\Controllers\Platform\TrialController;
use App\Http\Controllers\Platform\PluginController;
use App\Http\Controllers\Platform\MarketplaceController;
use App\Http\Controllers\Platform\TransactionController;
use App\Http\Controllers\Platform\RefundController;
use App\Http\Controllers\Platform\StatisticsController;
use App\Http\Controllers\Platform\StorageController;
use App\Http\Controllers\Platform\RoleController;
use App\Http\Controllers\Platform\TicketController;
use App\Http\Controllers\Platform\BroadcastController;
use App\Http\Controllers\Platform\ApiIntegrationController;
use App\Http\Controllers\Platform\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Routes available on all domains (for local development)
Route::group([], function () {
    Route::get('/', [LandingController::class , 'index'])->name('landing');

    // Foundation SAAS Registration Routes (Clean 5-Step)
    Route::get('/register', [FoundationRegistrationController::class , 'step1'])->name('register');
    Route::get('/register-foundation', [FoundationRegistrationController::class , 'step1'])->name('register.foundation');
    Route::get('/register-foundation/step1', [FoundationRegistrationController::class , 'step1'])->name('register.foundation.step1');
    Route::post('/register-foundation/step1', [FoundationRegistrationController::class , 'postStep1'])->name('register.foundation.step1.post');
    Route::get('/register-foundation/step2', [FoundationRegistrationController::class , 'step2'])->name('register.foundation.step2');
    Route::post('/register-foundation/step2', [FoundationRegistrationController::class , 'postStep2'])->name('register.foundation.step2.post');
    Route::get('/register-foundation/step3', [FoundationRegistrationController::class , 'step3'])->name('register.foundation.step3');
    Route::post('/register-foundation/step3', [FoundationRegistrationController::class , 'postStep3'])->name('register.foundation.step3.post');
    Route::get('/register-foundation/step4', [FoundationRegistrationController::class , 'step4'])->name('register.foundation.step4');
    Route::post('/register-foundation/step4', [FoundationRegistrationController::class , 'postStep4'])->name('register.foundation.step4.post');
    Route::get('/register-foundation/success', [FoundationRegistrationController::class , 'success'])->name('register.foundation.success');
    Route::post('/register-foundation/reset', [FoundationRegistrationController::class , 'reset'])->name('register.foundation.reset');
    
    // API Routes for Foundation Registration
    Route::get('/api/check-email', [FoundationRegistrationController::class , 'checkEmail'])->name('register.foundation.check-email');
    Route::get('/api/regencies', [FoundationRegistrationController::class , 'getRegencies'])->name('register.foundation.regencies');
    Route::get('/api/plugins', [FoundationRegistrationController::class , 'getPluginsByIds'])->name('register.foundation.plugins');
    
    Route::get('/registration-success', [FoundationRegistrationController::class , 'success'])->name('registration.success');

    Route::get('/trial-expired', function () {
            return view('trial-expired');
        }
        )->name('trial.expired');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

            // Platform Admin Routes (Dilindungi oleh check role platform_admin)
            Route::middleware([\App\Http\Middleware\PlatformAdminMiddleware::class])->prefix('platform')->name('platform.')->group(function () {
                    // Dashboard shortcut for platform area
                    Route::get('/', function () {
                            return redirect()->route('platform.foundations.index');
                        }
                        );
                        Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');
                        Route::get('/foundations', [FoundationController::class , 'index'])->name('foundations.index');
                        Route::get('/foundations/create', [FoundationController::class , 'create'])->name('foundations.create');
                        Route::post('/foundations', [FoundationController::class , 'store'])->name('foundations.store');
                        Route::post('/foundations/send-notification', [FoundationController::class , 'sendNotification'])->name('foundations.send-notification');
                        Route::get('/foundations/active', [FoundationController::class , 'activeFoundations'])->name('foundations.active');
                        Route::get('/foundations/suspended', [FoundationController::class , 'suspendedFoundations'])->name('foundations.suspended');
                        Route::get('/foundations/{foundation}', [FoundationController::class , 'show'])->name('foundations.show');
                        Route::get('/foundations/{foundation}/edit', [FoundationController::class , 'edit'])->name('foundations.edit');
                        Route::put('/foundations/{foundation}', [FoundationController::class , 'update'])->name('foundations.update');
                        Route::delete('/foundations/{foundation}', [FoundationController::class , 'destroy'])->name('foundations.destroy');
                        Route::post('/foundations/{foundation}/approve', [ApproveYayasanController::class , 'approve'])->name('foundations.approve');
                        Route::post('/foundations/{foundation}/reject', [FoundationController::class , 'reject'])->name('foundations.reject');

                        // Quick Action Routes
                        Route::post('/foundations/{foundation}/suspend', [FoundationController::class , 'suspend'])->name('foundations.suspend');
                        Route::post('/foundations/{foundation}/activate', [FoundationController::class , 'activate'])->name('foundations.activate');
                        Route::post('/foundations/{foundation}/extend-trial', [FoundationController::class , 'extendTrial'])->name('foundations.extend-trial');
                        Route::post('/foundations/{foundation}/convert', [FoundationController::class , 'convertToActive'])->name('foundations.convert');

                        // Registration Management Routes
                        Route::get('/registrations', [RegistrationController::class , 'index'])->name('registrations.index');
                        Route::get('/registrations/{registration}', [RegistrationController::class , 'show'])->name('registrations.show');
                        Route::post('/registrations/{registration}/approve', [RegistrationController::class , 'approve'])->name('registrations.approve');
                        Route::post('/registrations/{registration}/reject', [RegistrationController::class , 'reject'])->name('registrations.reject');
                        Route::post('/registrations/{registration}/verify-documents', [RegistrationController::class , 'verifyDocuments'])->name('registrations.verify-documents');
                        Route::post('/registrations/approve-all-pending', [RegistrationController::class , 'approveAllPending'])->name('registrations.approve-all-pending');
                        Route::post('/registrations/send-reminder', [RegistrationController::class , 'sendReminder'])->name('registrations.send-reminder');

                        // Subscription Management Routes
                        Route::get('/subscriptions', [SubscriptionController::class , 'index'])->name('subscriptions.index');
                        Route::get('/subscriptions/{subscription}', [SubscriptionController::class , 'show'])->name('subscriptions.show');
                        Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class , 'cancel'])->name('subscriptions.cancel');
                        Route::post('/subscriptions/{subscription}/reactivate', [SubscriptionController::class , 'reactivate'])->name('subscriptions.reactivate');

                        // Trial Management Routes
                        Route::get('/trials', [TrialController::class , 'index'])->name('trials.index');
                        Route::get('/trials/{trial}', [TrialController::class , 'show'])->name('trials.show');
                        Route::post('/trials/{trial}/extend', [TrialController::class , 'extend'])->name('trials.extend');
                        Route::post('/trials/{trial}/convert', [TrialController::class , 'convert'])->name('trials.convert');

                        // Plugin Management Routes
                        Route::get('/plugins', [PluginController::class , 'index'])->name('plugins.index');
                        Route::get('/plugins/active', [PluginController::class , 'active'])->name('plugins.active');
                        Route::get('/plugins/{plugin}', [PluginController::class , 'show'])->name('plugins.show');
                        Route::get('/plugins/{plugin}/edit', [PluginController::class , 'edit'])->name('plugins.edit');
                        Route::put('/plugins/{plugin}', [PluginController::class , 'update'])->name('plugins.update');
                        Route::patch('/plugins/{plugin}/price', [PluginController::class , 'updatePrice'])->name('plugins.update-price');
                        Route::patch('/plugins/{plugin}/status', [PluginController::class , 'updateStatus'])->name('plugins.update-status');
                        Route::post('/plugins/{plugin}/install', [PluginController::class , 'install'])->name('plugins.install');
                        Route::post('/plugins/{plugin}/uninstall', [PluginController::class , 'uninstall'])->name('plugins.uninstall');
                        Route::post('/plugins/{plugin}/activate', [PluginController::class , 'activate'])->name('plugins.activate');
                        Route::post('/plugins/{plugin}/deactivate', [PluginController::class , 'deactivate'])->name('plugins.deactivate');

                        // Attendance Plugin Routes
                        Route::get('/attendance', [\App\Http\Controllers\Platform\AttendanceController::class , 'index'])->name('attendance.index');
                        Route::post('/attendance/install', [\App\Http\Controllers\Platform\AttendanceController::class , 'install'])->name('attendance.install');
                        Route::post('/attendance/uninstall', [\App\Http\Controllers\Platform\AttendanceController::class , 'uninstall'])->name('attendance.uninstall');
                        Route::post('/attendance/toggle', [\App\Http\Controllers\Platform\AttendanceController::class , 'toggle'])->name('attendance.toggle');

                        // Marketplace Routes
                        Route::get('/marketplace', [MarketplaceController::class , 'index'])->name('marketplace.index');
                        Route::get('/marketplace/{plugin}', [MarketplaceController::class , 'show'])->name('marketplace.show');
                        Route::post('/marketplace/{plugin}/purchase', [MarketplaceController::class , 'purchase'])->name('marketplace.purchase');
                        Route::post('/marketplace/{plugin}/install', [MarketplaceController::class , 'install'])->name('marketplace.install');

                        // Invoice Management Routes
                        Route::get('/invoices', [InvoiceController::class , 'index'])->name('invoices.index');
                        Route::get('/invoices/{foundation}', [InvoiceController::class , 'show'])->name('invoices.show');
                        Route::post('/invoices/{foundation}/generate', [InvoiceController::class , 'generate'])->name('invoices.generate');
                        Route::post('/invoices/{foundation}/send', [InvoiceController::class , 'send'])->name('invoices.send');

                        // Payment Link Routes
                        Route::get('/invoices/{invoice}/payment-link', [InvoiceController::class , 'paymentLink'])->name('invoices.payment-link');
                        Route::post('/invoices/{invoice}/send-payment-link', [InvoiceController::class , 'sendPaymentLink'])->name('invoices.send-payment-link');
                        Route::post('/invoices/{invoice}/verify-payment', [InvoiceController::class , 'verifyPayment'])->name('invoices.verify-payment');

                        // Transaction Management Routes
                        Route::get('/transactions', [TransactionController::class , 'index'])->name('transactions.index');
                        Route::get('/transactions/{transaction}', [TransactionController::class , 'show'])->name('transactions.show');
                        Route::get('/transactions/create', [TransactionController::class , 'create'])->name('transactions.create');
                        Route::post('/transactions', [TransactionController::class , 'store'])->name('transactions.store');

                        // Refund Management Routes
                        Route::get('/refunds', [RefundController::class , 'index'])->name('refunds.index');
                        Route::get('/refunds/{refund}', [RefundController::class , 'show'])->name('refunds.show');
                        Route::get('/refunds/create', [RefundController::class , 'create'])->name('refunds.create');
                        Route::post('/refunds', [RefundController::class , 'store'])->name('refunds.store');
                        Route::post('/refunds/{refund}/approve', [RefundController::class , 'approve'])->name('refunds.approve');
                        Route::post('/refunds/{refund}/reject', [RefundController::class , 'reject'])->name('refunds.reject');

                        // Statistics Routes
                        Route::get('/statistics', [StatisticsController::class , 'index'])->name('statistics.index');
                        Route::get('/statistics/foundations', [StatisticsController::class , 'foundations'])->name('statistics.foundations');
                        Route::get('/statistics/revenue', [StatisticsController::class , 'revenue'])->name('statistics.revenue');
                        Route::get('/statistics/growth', [StatisticsController::class , 'growth'])->name('statistics.growth');

                        // Storage Monitoring Routes
                        Route::get('/storage', [StorageController::class , 'index'])->name('storage.index');
                        Route::get('/storage/usage', [StorageController::class , 'usage'])->name('storage.usage');
                        Route::post('/storage/cleanup', [StorageController::class , 'cleanup'])->name('storage.cleanup');

                        // Role & Permission Routes
                        Route::get('/roles', [RoleController::class , 'index'])->name('roles.index');
                        Route::get('/roles/create', [RoleController::class , 'create'])->name('roles.create');
                        Route::post('/roles', [RoleController::class , 'store'])->name('roles.store');
                        Route::get('/roles/{role}', [RoleController::class , 'show'])->name('roles.show');
                        Route::get('/roles/{role}/edit', [RoleController::class , 'edit'])->name('roles.edit');
                        Route::put('/roles/{role}', [RoleController::class , 'update'])->name('roles.update');
                        Route::delete('/roles/{role}', [RoleController::class , 'destroy'])->name('roles.destroy');

                        // Ticket Support Routes
                        Route::get('/tickets', [TicketController::class , 'index'])->name('tickets.index');
                        Route::get('/tickets/create', [TicketController::class , 'create'])->name('tickets.create');
                        Route::post('/tickets', [TicketController::class , 'store'])->name('tickets.store');
                        Route::get('/tickets/{ticket}', [TicketController::class , 'show'])->name('tickets.show');
                        Route::post('/tickets/{ticket}/respond', [TicketController::class , 'respond'])->name('tickets.respond');
                        Route::post('/tickets/{ticket}/close', [TicketController::class , 'close'])->name('tickets.close');
                        Route::post('/tickets/{ticket}/reopen', [TicketController::class , 'reopen'])->name('tickets.reopen');

                        // Broadcast Routes
                        Route::get('/broadcasts', [BroadcastController::class , 'index'])->name('broadcasts.index');
                        Route::get('/broadcasts/create', [BroadcastController::class , 'create'])->name('broadcasts.create');
                        Route::post('/broadcasts', [BroadcastController::class , 'store'])->name('broadcasts.store');
                        Route::get('/broadcasts/{broadcast}', [BroadcastController::class , 'show'])->name('broadcasts.show');
                        Route::post('/broadcasts/{broadcast}/send', [BroadcastController::class , 'send'])->name('broadcasts.send');

                        // API Integration Routes (expand existing payment-gateways)
                        Route::get('/api-integrations', [ApiIntegrationController::class , 'index'])->name('api-integrations.index');
                        Route::get('/api-integrations/create', [ApiIntegrationController::class , 'create'])->name('api-integrations.create');
                        Route::post('/api-integrations', [ApiIntegrationController::class , 'store'])->name('api-integrations.store');
                        Route::get('/api-integrations/{integration}', [ApiIntegrationController::class , 'show'])->name('api-integrations.show');
                        Route::get('/api-integrations/{integration}/edit', [ApiIntegrationController::class , 'edit'])->name('api-integrations.edit');
                        Route::put('/api-integrations/{integration}', [ApiIntegrationController::class , 'update'])->name('api-integrations.update');
                        Route::delete('/api-integrations/{integration}', [ApiIntegrationController::class , 'destroy'])->name('api-integrations.destroy');
                        Route::post('/api-integrations/{integration}/test', [ApiIntegrationController::class , 'test'])->name('api-integrations.test');

                        // Activity Log Routes
                        Route::get('/activity-logs', [ActivityLogController::class , 'index'])->name('activity-logs.index');
                        Route::get('/activity-logs/{log}', [ActivityLogController::class , 'show'])->name('activity-logs.show');
                        Route::post('/activity-logs/cleanup', [ActivityLogController::class , 'cleanup'])->name('activity-logs.cleanup');
                        Route::get('/activity-logs/export', [ActivityLogController::class , 'export'])->name('activity-logs.export');

                        // Existing Routes
                        Route::get('/schools', [SchoolController::class , 'index'])->name('schools.index');
                        Route::get('/students', [StudentController::class , 'index'])->name('students.index');
                        Route::get('/plans', [PlanController::class , 'index'])->name('plans.index');
                        Route::get('/plans/create', [PlanController::class , 'create'])->name('plans.create');
                        Route::post('/plans', [PlanController::class , 'store'])->name('plans.store');
                        Route::get('/plans/{plan}/edit', [PlanController::class , 'edit'])->name('plans.edit');
                        Route::put('/plans/{plan}', [PlanController::class , 'update'])->name('plans.update');
                        Route::delete('/plans/{plan}', [PlanController::class , 'destroy'])->name('plans.destroy');
                        Route::get('/invoices', [InvoiceController::class , 'index'])->name('invoices.index');
                        Route::get('/users', [UserController::class , 'index'])->name('users.index');
                        Route::get('/users/create', [UserController::class , 'create'])->name('users.create');
                        Route::post('/users', [UserController::class , 'store'])->name('users.store');
                        Route::get('/users/{user}', [UserController::class , 'show'])->name('users.show');
                        Route::get('/users/{user}/edit', [UserController::class , 'edit'])->name('users.edit');
                        Route::put('/users/{user}', [UserController::class , 'update'])->name('users.update');
                        Route::delete('/users/{user}', [UserController::class , 'destroy'])->name('users.destroy');

                        // Payment Gateway Management
                        Route::get('/payment-gateways', [PaymentGatewayController::class , 'index'])->name('payment-gateways.index');
                        Route::get('/payment-gateways/create', [PaymentGatewayController::class , 'create'])->name('payment-gateways.create');
                        Route::post('/payment-gateways', [PaymentGatewayController::class , 'store'])->name('payment-gateways.store');
                        Route::get('/payment-gateways/{paymentGateway}', [PaymentGatewayController::class , 'show'])->name('payment-gateways.show');
                        Route::get('/payment-gateways/{paymentGateway}/edit', [PaymentGatewayController::class , 'edit'])->name('payment-gateways.edit');
                        Route::put('/payment-gateways/{paymentGateway}', [PaymentGatewayController::class , 'update'])->name('payment-gateways.update');
                        Route::delete('/payment-gateways/{paymentGateway}', [PaymentGatewayController::class , 'destroy'])->name('payment-gateways.destroy');
                        Route::post('/payment-gateways/{paymentGateway}/test', [PaymentGatewayController::class , 'testConnection'])->name('payment-gateways.test');
                        Route::post('/payment-gateways/{paymentGateway}/toggle', [PaymentGatewayController::class , 'toggleStatus'])->name('payment-gateways.toggle');

                        // Payment Management
                        Route::get('/payments', [PaymentController::class , 'index'])->name('payments.index');
                        Route::get('/payments/create', [PaymentController::class , 'create'])->name('payments.create');
                        Route::post('/payments', [PaymentController::class , 'store'])->name('payments.store');
                        Route::get('/payments/{payment}', [PaymentController::class , 'show'])->name('payments.show');
                        Route::post('/payments/{payment}/confirm', [PaymentController::class , 'confirm'])->name('payments.confirm');
                        Route::post('/payments/{payment}/reject', [PaymentController::class , 'reject'])->name('payments.reject');

                        // Platform Payment (for subscription payments)
                        Route::post('/platform-payments/{payment}/confirm', [PaymentController::class , 'confirmPlatformPayment'])
                            ->name('platform-payments.confirm');

                        // Recurring Payments
                        Route::get('/recurring-payments', [RecurringPaymentController::class , 'index'])->name('recurring-payments.index');
                        Route::get('/recurring-payments/create', [RecurringPaymentController::class , 'create'])->name('recurring-payments.create');
                        Route::post('/recurring-payments', [RecurringPaymentController::class , 'store'])->name('recurring-payments.store');
                        Route::get('/recurring-payments/{recurringPayment}', [RecurringPaymentController::class , 'show'])->name('recurring-payments.show');
                        Route::get('/recurring-payments/{recurringPayment}/edit', [RecurringPaymentController::class , 'edit'])->name('recurring-payments.edit');
                        Route::put('/recurring-payments/{recurringPayment}', [RecurringPaymentController::class , 'update'])->name('recurring-payments.update');
                        Route::post('/recurring-payments/{recurringPayment}/pause', [RecurringPaymentController::class , 'pause'])->name('recurring-payments.pause');
                        Route::post('/recurring-payments/{recurringPayment}/resume', [RecurringPaymentController::class , 'resume'])->name('recurring-payments.resume');
                        Route::post('/recurring-payments/{recurringPayment}/cancel', [RecurringPaymentController::class , 'cancel'])->name('recurring-payments.cancel');
                        Route::post('/recurring-payments/process-scheduled', [RecurringPaymentController::class , 'processScheduled'])->name('recurring-payments.process');

                        // Webhook Management
                        Route::get('/webhooks', [WebhookController::class , 'logs'])->name('webhooks.index');
                        Route::get('/webhooks/logs/{log}/details', [WebhookController::class , 'getLogDetails'])->name('webhooks.logs.details');
                        Route::post('/webhooks/logs/{log}/retry', [WebhookController::class , 'retryWebhook'])->name('webhooks.logs.retry');
                        Route::post('/webhook/{gateway}', [WebhookController::class , 'handle'])->name('webhook.handle');
                        Route::get('/settings', [SettingController::class , 'index'])->name('settings.index');
                        Route::post('/settings', [SettingController::class , 'update'])->name('settings.update');

                        // Verifikasi Email Yayasan
                        Route::get('/email-verifications', [EmailVerificationController::class , 'index'])->name('email-verifications.index');
                        Route::get('/email-verifications/{foundation}/users', [EmailVerificationController::class , 'getUsers'])->name('email-verifications.users');
                        Route::post('/email-verifications/verify-by-email', [EmailVerificationController::class , 'verifyByEmail'])->name('email-verifications.verify-by-email');
                        Route::post('/email-verifications/{user}/verify', [EmailVerificationController::class , 'verify'])->name('email-verifications.verify');
                        Route::post('/email-verifications/{user}/resend', [EmailVerificationController::class , 'resend'])->name('email-verifications.resend');
                    }
                    );
                }
                );

                require __DIR__ . '/auth.php';                require __DIR__ . '/cbt.php';
            });
