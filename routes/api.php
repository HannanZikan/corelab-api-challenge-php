<?php

use App\Http\Controllers\Api\TodoListController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [UserAuthController::class, 'changePassword']);
    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::group(['prefix' => 'todo-list'], function () {
        Route::get('/', [TodoListController::class, 'index']);
        Route::post('/store', [TodoListController::class, 'store']);
        Route::post('/update/{id}', [TodoListController::class, 'update']);
        Route::post('/destroy', [TodoListController::class, 'destroy']);
    });
});
