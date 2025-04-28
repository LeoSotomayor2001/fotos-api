<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);    
    Route::post('register', [UserController::class, 'store']);
});

Route::middleware(['isAuth'])->group(function () {
    //Rutas de los usuarios
    Route::get('/users',[UserController::class,'index']);
    Route::get('/users/{username}',[UserController::class,'show']);
    Route::post('/users/{id}',[UserController::class,'update']);
    //Post
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    //Logout
    Route::post('logout', action:LogoutController::class);

});

Route::get('/imagen/{filename}', [ImageController::class, 'show']);
Route::get('/post/{filename}', [ImageController::class, 'showPost']);

