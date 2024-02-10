<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VillaController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth:seller');
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware('auth:seller');

Route::post('logout', [AuthController::class, 'logout']);

Route::prefix('villa')->group(function() {
    Route::get('', [VillaController::class, 'index']);
    Route::get('{id}',[VillaController::class, 'detail']);
    Route::post('',[VillaController::class, 'create']);
    Route::put('{id}',[VillaController::class, 'edit']);
});