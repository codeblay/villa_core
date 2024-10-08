<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FirebaseController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\VillaController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:buyer', 'is_verified']);
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:buyer', 'is_verified']);

Route::post('logout', [AuthController::class, 'logout']);

Route::put('update-fcm', [AuthController::class, 'updateFcm']);

Route::prefix('password')->group(function(){
    Route::post('reset', [AuthController::class, 'resetPassword']);
    Route::post('forgot', [AuthController::class, 'forgotPassword'])->withoutMiddleware(['auth:buyer', 'is_verified']);
});

Route::prefix('transaction')->group(function(){
    Route::get('', [TransactionController::class, 'list']);
    
    Route::prefix('{id}')->group(function(){
        Route::get('', [TransactionController::class, 'detail']);
        Route::get('sync', [TransactionController::class, 'sync']);
        Route::post('cancel', [TransactionController::class, 'cancel']);
    });
});

Route::prefix('villa')->group(function(){
    Route::post('rate', [VillaController::class, 'rate']);
    Route::post('booking', [VillaController::class, 'booking']);
    Route::get('check/{id}', [VillaController::class, 'check']);
});

Route::prefix('profile')->group(function () {
    Route::get('', [ProfileController::class, 'profileBuyer']);
});

Route::prefix('fcm')->group(function () {
    Route::post('send', [FirebaseController::class, 'send']);
});
