<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [UserController::class, 'store']);
});

Route::middleware(['isAuth'])->group(function () {
    //Rutas de los usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{username}', [UserController::class, 'show']);
    Route::post('/users/search', [UserController::class, 'search']);
    Route::post('/users/{id}', [UserController::class, 'update']);

    //Reacciones
    Route::post('/posts/reaction', [ReactionController::class, 'store']);
    Route::delete('/posts/reaction', [ReactionController::class, 'destroy']);
    //Post
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);


    //Comentarios 
    Route::apiResource('/comments', CommentController::class);
    //Logout
    Route::post('logout', action: LogoutController::class);

    //Siguiendo a usuarios
    Route::get('/followers/suggested', [UserController::class, 'suggestedUsers']);
    Route::post('/{user:username}/follow', [FollowerController::class, 'store']);
    Route::delete('/{user:username}/follow', [FollowerController::class, 'destroy']);

    //Notificaciones
    Route::get('/notifications', [NotificationController::class, 'notifications']);
    Route::get('/notifications/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
});

Route::get('/imagen/{filename}', [ImageController::class, 'show']);
Route::get('/post/{filename}', [ImageController::class, 'showPost']);
