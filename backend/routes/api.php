<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role.guard'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::prefix('routes')->group(function () {
        Route::post('index', [RouteController::class, 'index']);
    });

    Route::prefix('roles')->group(function () {
        Route::post('index', [RoleController::class, 'index']);
    });

    Route::prefix('abilities')->group(function () {
        Route::post('store', [AbilityController::class, 'store']);
        Route::patch('update', [AbilityController::class, 'update']);
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
