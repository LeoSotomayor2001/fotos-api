<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);    
    Route::post('register', [UserController::class, 'store']);
});

Route::middleware(['isAuth'])->group(function () {
    Route::get('/users',[UserController::class,'index']);
    Route::post('logout', action:LogoutController::class);

});

