<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VillaController;
use App\MyConst;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware('admin')->middleware('guest')->group(function () {
    Route::view('login', 'pages.login')->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('', [DashboardController::class, 'index'])->name('dashboard');
Route::get('villa', [VillaController::class, 'index'])->name('villa');
Route::get('villa/{id}', [VillaController::class, 'detail'])->name('villa.detail');

Route::prefix('user')->group(function () {
    Route::get(MyConst::USER_SELLER, [SellerController::class, 'index'])->name('user.seller');
    Route::get(MyConst::USER_BUYER, [BuyerController::class, 'index'])->name('user.buyer');
});

Route::prefix('verification')->group(function () {
    Route::get('account', [SellerController::class, 'verification'])->name('verification.account');
    Route::post('account/{id}/accept', [SellerController::class, 'verificationAccept'])->name('verification.account.accept');
    Route::post('account/{id}/deny', [SellerController::class, 'verificationDeny'])->name('verification.account.deny');
});

Route::prefix('transaction')->group(function () {
    Route::get('rent', [TransactionController::class, 'rent'])->name('transaction.rent');
    Route::view('withdrawal', 'pages.admin.transaction.withdrawal')->name('transaction.withdrawal');
});

Route::prefix('master')->group(function () {
    Route::get('facility', [MasterController::class, 'facility'])->name('master.facility');
    Route::post('facility', [MasterController::class, 'facilityCreate'])->name('master.facility.create');

    Route::prefix('destination')->group(function () {
        Route::get('category', [MasterController::class, 'destinationCategory'])->name('master.destination.category');
        Route::post('category', [MasterController::class, 'destinationCategoryCreate'])->name('master.destination.category.create');
        
        Route::prefix('list')->group(function () {
            Route::get('', [MasterController::class, 'destinationList'])->name('master.destination.list');
            Route::get('{id}', [MasterController::class, 'destinationListDetail'])->name('master.destination.list.detail');
            Route::get('{id}/edit', [MasterController::class, 'destinationListEdit'])->name('master.destination.list.edit');
            Route::post('', [MasterController::class, 'destinationListCreate'])->name('master.destination.list.create');
            Route::put('', [MasterController::class, 'destinationListUpdate'])->name('master.destination.list.update');
        });
    });

    Route::prefix('bank')->group(function () {
        Route::get('', [MasterController::class, 'bank'])->name('master.bank');
        Route::put('', [MasterController::class, 'bankUpdate'])->name('master.bank.update');
    });

    Route::prefix('document')->group(function () {
        Route::get('', [MasterController::class, 'document'])->name('master.document'); 
        Route::post('', [MasterController::class, 'documentUpdate'])->name('master.document.update');
    });
});
