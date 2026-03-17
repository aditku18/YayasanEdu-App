<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| User Module Routes
|--------------------------------------------------------------------------
|
| Routes for the User module. These routes are automatically loaded
| by the ModuleServiceProvider.
|
*/

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
