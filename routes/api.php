<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users',[UserController::class,'index']);
// Route::middleware(['auth:sanctum'])->group(function () {

// });

