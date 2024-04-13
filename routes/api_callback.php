<?php

use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\SendtalkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('sendtalk', [SendtalkController::class, 'otp']);
Route::post('midtrans', [MidtransController::class, 'notification']);
