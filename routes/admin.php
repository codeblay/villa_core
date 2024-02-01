<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return 'login admin';
})->name('admin.login')->withoutMiddleware('admin');

Route::withoutMiddleware('admin')->group(function(){
    Route::view('', 'pages.admin.dashboard')->name('admin.dashboard');
    Route::view('villa', 'pages.admin.villa')->name('admin.villa');
    
    Route::prefix('user')->group(function(){
        Route::view('seller', 'pages.admin.user.seller')->name('admin.user.seller');
        Route::view('buyer', 'pages.admin.user.buyer')->name('admin.user.buyer');
    });

    Route::prefix('verification')->group(function(){
        Route::view('account', 'pages.admin.verification.account')->name('admin.verification.account');
    });

    Route::prefix('transaction')->group(function(){
        Route::view('rent', 'pages.admin.transaction.rent')->name('admin.transaction.rent');
        Route::view('withdrawal', 'pages.admin.transaction.withdrawal')->name('admin.transaction.withdrawal');
    });

    Route::prefix('villa')->group(function(){
        Route::view('facility', 'pages.admin.villa.facility')->name('admin.villa.facility');

        Route::prefix('destination')->group(function(){
            Route::view('category', 'pages.admin.villa.destination.category')->name('admin.villa.destination.category');
            Route::view('list', 'pages.admin.villa.destination.list')->name('admin.villa.destination.list');
        });
    });
});
