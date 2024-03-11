<?php

use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\VillaController;
use App\Services\Sendtalk\Callback\SendtalkCallbackService;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('villa')->group(function() {
    Route::get('', [VillaController::class, 'list']);
    Route::get('slider', [VillaController::class, 'slider']);
    Route::get('{id}', [VillaController::class, 'detail']);
});

Route::prefix('destination')->group(function() {
    Route::prefix('category')->group(function() {
        Route::get('{id}', [DestinationController::class, 'listByCategory']);
    });

    Route::get('{id}', [DestinationController::class, 'detail']);
});

Route::post('callback_sendtalk', [SendtalkCallbackService::class, 'otp'])->withoutMiddleware('app_key');