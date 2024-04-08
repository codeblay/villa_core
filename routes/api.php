<?php

use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\FacilityController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\Select2Controller;
use App\Http\Controllers\API\VillaController;
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

Route::prefix('facility')->group(function() {
    Route::get('dropdown', [FacilityController::class, 'dropdown']);
});

Route::prefix('city')->group(function() {
    Route::get('dropdown', [CityController::class, 'dropdown']);
});

Route::get('payment', [PaymentController::class, 'list']);

Route::get('banner', [BannerController::class, 'get']);

Route::prefix('select2')->withoutMiddleware('app_key')->group(function(){
    Route::get('villa', [Select2Controller::class, 'villa'])->name('select2.villa');
    Route::get('location', [Select2Controller::class, 'location'])->name('select2.location');
    Route::get('location/{id}', [Select2Controller::class, 'locationDetail'])->name('select2.location.detail');
    Route::get('seller', [Select2Controller::class, 'seller'])->name('select2.seller');
    Route::get('seller/{id}', [Select2Controller::class, 'sellerDetail'])->name('select2.seller.detail');
});