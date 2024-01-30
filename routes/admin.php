<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return 'login admin';
})->name('admin.login')->withoutMiddleware('admin');

Route::get('/', function () {
    return 'admin';
});
