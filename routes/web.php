<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return strtolower(config('app.env')) == 'production' ? view('welcome') : 'DEVELOPMENT';
});

Route::get('verification', [AuthController::class, 'verification'])->name('verification');
Route::get('reset', [AuthController::class, 'reset'])->name('reset');
Route::post('reset', [AuthController::class, 'resetPassword'])->name('reset');
Route::get('resetCancel', [AuthController::class, 'resetPasswordCancel'])->name('reset.cancel');
