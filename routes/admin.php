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
    Route::view('login', 'pages.login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('villa')->group(function () {
    Route::get('', [VillaController::class, 'index'])->name('villa');
    
    Route::prefix('{id}')->group(function () {
        Route::get('', [VillaController::class, 'detail'])->name('villa.detail');
        Route::put('bypass_rating', [VillaController::class, 'bypassRating'])->name('villa.bypass-rating');
    });
});

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
    Route::prefix('rent')->group(function () {
        Route::get('', [TransactionController::class, 'rent'])->name('transaction.rent');
        Route::post('{id}/sync', [TransactionController::class, 'rentSync'])->name('transaction.rentSync');
    });
});

Route::prefix('master')->group(function () {
    Route::prefix('facility')->group(function () {
        Route::get('', [MasterController::class, 'facility'])->name('master.facility');
        Route::post('', [MasterController::class, 'facilityCreate'])->name('master.facility.create');
        Route::put('{id}', [MasterController::class, 'facilityUpdate'])->name('master.facility.update');
        Route::delete('{id}', [MasterController::class, 'facilityDelete'])->name('master.facility.delete');
    });

    Route::prefix('destination')->group(function () {
        
        Route::prefix('category')->group(function () {
            Route::get('', [MasterController::class, 'destinationCategory'])->name('master.destination.category');
            Route::post('', [MasterController::class, 'destinationCategoryCreate'])->name('master.destination.category.create');
            Route::put('{id}', [MasterController::class, 'destinationCategoryUpdate'])->name('master.destination.category.update');
            Route::delete('{id}', [MasterController::class, 'destinationCategoryDelete'])->name('master.destination.category.delete');
        });

        Route::prefix('list')->group(function () {
            Route::get('', [MasterController::class, 'destinationList'])->name('master.destination.list');
            Route::get('{id}', [MasterController::class, 'destinationListDetail'])->name('master.destination.list.detail');
            Route::get('{id}/edit', [MasterController::class, 'destinationListEdit'])->name('master.destination.list.edit');
            Route::delete('{id}', [MasterController::class, 'destinationListDelete'])->name('master.destination.list.delete');
            Route::post('', [MasterController::class, 'destinationListCreate'])->name('master.destination.list.create');
            Route::put('', [MasterController::class, 'destinationListUpdate'])->name('master.destination.list.update');
        });
    });

    Route::prefix('payment')->group(function () {
        Route::get('', [MasterController::class, 'bank'])->name('master.payment');
        Route::put('', [MasterController::class, 'bankUpdate'])->name('master.payment.update');
    });

    Route::prefix('document')->group(function () {
        Route::get('', [MasterController::class, 'document'])->name('master.document'); 
        Route::post('', [MasterController::class, 'documentUpdate'])->name('master.document.update');
    });

    Route::prefix('banner')->group(function () {
        Route::get('', [MasterController::class, 'banner'])->name('master.banner'); 
        Route::post('', [MasterController::class, 'bannerUpdate'])->name('master.banner.update');
        Route::delete('', [MasterController::class, 'bannerDelete'])->name('master.banner.delete');
    });
});
