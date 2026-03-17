<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Student\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Student Module Routes
|--------------------------------------------------------------------------
|
| Routes for the Student module.
| NOTE: Routes are commented out - controllers not yet implemented
|
*/

// Route::prefix('students')->name('students.')->group(function () {
//     // List & CRUD
//     Route::get('/', [StudentController::class, 'index'])->name('index');
//     Route::post('/', [StudentController::class, 'store'])->name('store');
//     Route::get('/{student}', [StudentController::class, 'show'])->name('show');
//     Route::put('/{student}', [StudentController::class, 'update'])->name('update');
//     Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
    
//     // Actions
//     Route::post('/import', [StudentController::class, 'import'])->name('import');
//     Route::get('/export', [StudentController::class, 'export'])->name('export');
//     Route::get('/template', [StudentController::class, 'downloadTemplate'])->name('template');
//     Route::post('/{student}/activate', [StudentController::class, 'activate'])->name('activate');
//     Route::post('/{student}/deactivate', [StudentController::class, 'deactivate'])->name('deactivate');
//     Route::post('/{student}/transfer', [StudentController::class, 'transfer'])->name('transfer');
    
//     // Search
//     Route::get('/search/results', [StudentController::class, 'search'])->name('search');
    
//     // Reports
//     Route::get('/statistics', [StudentController::class, 'statistics'])->name('statistics');
//     Route::get('/outstanding-balance', [StudentController::class, 'outstandingBalance'])->name('outstanding-balance');
// });
