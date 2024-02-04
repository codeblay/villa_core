<?php

use App\Models\User;
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

Route::get('u', function(){
    $user = User::query()->get();
    $user = $user->map(function($user)  {
        return [
            'id' => $user->id,
            'text' => $user->name,
        ];
    })->toArray();

    return response()->json($user);
})->name('search.user');