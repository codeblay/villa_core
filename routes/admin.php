<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\VillaController;
use App\MyConst;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware('admin')->middleware('guest')->group(function () {
    Route::view('login', 'pages.login')->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::view('', 'pages.admin.dashboard')->name('dashboard');
Route::get('villa', [VillaController::class, 'index'])->name('villa');

Route::prefix('user')->group(function () {
    Route::get(MyConst::USER_SELLER, [SellerController::class, 'index'])->name('user.seller');
    Route::get(MyConst::USER_BUYER, [BuyerController::class, 'index'])->name('user.buyer');
});

Route::prefix('verification')->group(function () {
    Route::get('account', [SellerController::class, 'verification'])->name('verification.account');
});

Route::prefix('transaction')->group(function () {
    Route::view('rent', 'pages.admin.transaction.rent')->name('transaction.rent');
    Route::view('withdrawal', 'pages.admin.transaction.withdrawal')->name('transaction.withdrawal');
});

Route::prefix('master')->group(function () {
    Route::get('facility', [MasterController::class, 'facility'])->name('master.facility');
    Route::post('facility', [MasterController::class, 'facilityCreate'])->name('master.facility.create');

    Route::prefix('destination')->group(function () {
        Route::get('category', [MasterController::class, 'destinationCategory'])->name('master.destination.category');
        Route::post('category', [MasterController::class, 'destinationCategoryCreate'])->name('master.destination.category.create');
        Route::get('list', [MasterController::class, 'destinationList'])->name('master.destination.list');
    });
});
