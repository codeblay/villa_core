<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\VillaController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth:buyer');
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware('auth:buyer');

Route::post('logout', [AuthController::class, 'logout']);

Route::prefix('transaction')->group(function(){
    Route::get('', [TransactionController::class, 'list']);
    Route::get('{id}', [TransactionController::class, 'detail']);
});

Route::prefix('villa')->group(function(){
    Route::post('rate', [VillaController::class, 'rate']);
    Route::post('booking', [VillaController::class, 'booking']);
});

Route::get('payment', [PaymentController::class, 'list']);