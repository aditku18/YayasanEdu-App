<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Finance Module Routes
|--------------------------------------------------------------------------
|
| Routes for the Finance module.
| NOTE: Routes are commented out - controllers not yet implemented
|
*/

// Route::prefix('finance')->name('finance.')->group(function () {
    
//     // Dashboard
//     Route::get('/', [\App\Modules\Finance\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
//     // Bill Types
//     Route::prefix('bill-types')->name('bill-types.')->group(function () {
//         Route::get('/', [\App\Modules\Finance\Http\Controllers\BillTypeController::class, 'index'])->name('index');
//         Route::post('/', [\App\Modules\Finance\Http\Controllers\BillTypeController::class, 'store'])->name('store');
//         Route::put('/{billType}', [\App\Modules\Finance\Http\Controllers\BillTypeController::class, 'update'])->name('update');
//         Route::delete('/{billType}', [\App\Modules\Finance\Http\Controllers\BillTypeController::class, 'destroy'])->name('destroy');
//     });
    
//     // Invoices
//     Route::prefix('invoices')->name('invoices.')->group(function () {
//         Route::get('/', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'index'])->name('index');
//         Route::post('/', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'store'])->name('store');
//         Route::post('/generate', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'generate'])->name('generate');
//         Route::get('/{invoice}', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'show'])->name('show');
//         Route::put('/{invoice}', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'update'])->name('update');
//         Route::delete('/{invoice}', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'destroy'])->name('destroy');
//         Route::get('/{invoice}/print', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'print'])->name('print');
//     });
    
//     // Payments
//     Route::prefix('payments')->name('payments.')->group(function () {
//         Route::get('/', [\App\Modules\Finance\Http\Controllers\PaymentController::class, 'index'])->name('index');
//         Route::post('/', [\App\Modules\Finance\Http\Controllers\PaymentController::class, 'store'])->name('store');
//         Route::post('/{payment}/confirm', [\App\Modules\Finance\Http\Controllers\PaymentController::class, 'confirm'])->name('confirm');
//         Route::post('/{payment}/reject', [\App\Modules\Finance\Http\Controllers\PaymentController::class, 'reject'])->name('reject');
//     });
    
//     // Expenses
//     Route::prefix('expenses')->name('expenses.')->group(function () {
//         Route::get('/', [\App\Modules\Finance\Http\Controllers\ExpenseController::class, 'index'])->name('index');
//         Route::post('/', [\App\Modules\Finance\Http\Controllers\ExpenseController::class, 'store'])->name('store');
//         Route::post('/{expense}/approve', [\App\Modules\Finance\Http\Controllers\ExpenseController::class, 'approve'])->name('approve');
//         Route::post('/{expense}/reject', [\App\Modules\Finance\Http\Controllers\ExpenseController::class, 'reject'])->name('reject');
//     });
    
//     // Reports
//     Route::prefix('reports')->name('reports.')->group(function () {
//         Route::get('/', [\App\Modules\Finance\Http\Controllers\ReportController::class, 'index'])->name('index');
//         Route::get('/export', [\App\Modules\Finance\Http\Controllers\ReportController::class, 'export'])->name('export');
//     });
// });
