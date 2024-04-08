<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\VillaController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:seller', 'is_verified']);
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:seller', 'is_verified']);

Route::post('logout', [AuthController::class, 'logout']);

Route::put('update-fcm', [AuthController::class, 'updateFcm']);

Route::prefix('password')->group(function(){
    Route::post('reset', [AuthController::class, 'resetPassword']);
    Route::post('forgot', [AuthController::class, 'forgotPassword'])->withoutMiddleware(['auth:seller', 'is_verified']);
});

Route::get('dashboard', [DashboardController::class, 'dashboard']);

Route::prefix('villa')->group(function () {
    Route::get('', [VillaController::class, 'listBySeller']);
    Route::post('', [VillaController::class, 'create']);

    Route::prefix('{id}')->group(function () {
        Route::get('', [VillaController::class, 'detail']);
        Route::post('update', [VillaController::class, 'edit']);
    });
});

Route::prefix('transaction')->group(function () {
    Route::get('', [TransactionController::class, 'listForSeller']);
    Route::prefix('{id}')->group(function () {
        Route::get('', [TransactionController::class, 'detail']);
        Route::post('accept', [TransactionController::class, 'accept']);
        Route::post('deny', [TransactionController::class, 'deny']);
    });
});

Route::prefix('profile')->group(function () {
    Route::get('', [ProfileController::class, 'profileSeller']);
});
