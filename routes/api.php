<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users',[UserController::class,'index']);
// Route::middleware(['auth:sanctum'])->group(function () {

// });

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);    
    Route::post('logout', action:LogoutController::class);
});

