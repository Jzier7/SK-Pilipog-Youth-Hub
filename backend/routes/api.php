<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurokController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role.guard'])->group(function () {

    Route::prefix('routes')->group(function () {
        Route::post('index', [RouteController::class, 'index']);
    });

    Route::prefix('user')->group(function () {
        Route::get('me', [UserController::class, 'me']);
    });

    Route::prefix('role')->group(function () {
        Route::post('index', [RoleController::class, 'index']);
    });

    Route::prefix('ability')->group(function () {
        Route::post('store', [AbilityController::class, 'store']);
        Route::patch('update', [AbilityController::class, 'update']);
    });


    Route::prefix('users')->group(function () {
        Route::post('store/admin', [UserController::class, 'store']);
        Route::delete('delete', [UserController::class, 'delete']);

        Route::prefix('update')->group(function () {
            Route::patch('data', [UserController::class, 'updateData']);
            Route::patch('status', [UserController::class, 'updateStatus']);
        });

        Route::prefix('retrieve')->group(function () {
            Route::get('all/users', [UserController::class, 'retrieveAllUsers']);
            Route::get('all/admins', [UserController::class, 'retrieveAllAdmins']);
            Route::get('one', [UserController::class, 'retrieveOne']);
        });

        Route::prefix('voters')->group(function () {
            Route::get('count', [UserController::class, 'getVotersCount']);
        });

        Route::prefix('per-purok')->group(function () {
            Route::get('count', [UserController::class, 'getCountPerPurok']);
        });
    });

    Route::prefix('announcement')->group(function () {
        Route::post('store', [AnnouncementController::class, 'store']);
        Route::patch('update', [AnnouncementController::class, 'update']);
        Route::delete('delete', [AnnouncementController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [AnnouncementController::class, 'retrieveAll']);
            Route::get('one', [AnnouncementController::class, 'retrieveOne']);
        });
    });

    Route::prefix('category')->group(function () {
        Route::post('store', [CategoryController::class, 'store']);
        Route::patch('update', [CategoryController::class, 'update']);
        Route::delete('delete', [AnnouncementController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [CategoryController::class, 'retrieveAll']);
            Route::get('one', [CategoryController::class, 'retrieveOne']);
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('purok')->group(function () {
    Route::get('retrieve/all', [PurokController::class, 'retrieveAll']);
});
