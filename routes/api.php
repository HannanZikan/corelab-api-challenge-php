<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [UserAuthController::class, 'changePassword']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
});
