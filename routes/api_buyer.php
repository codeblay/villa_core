<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth:buyer');
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware('auth:buyer');

Route::post('logout', [AuthController::class, 'logout']);