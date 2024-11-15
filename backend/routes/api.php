<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurokController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumCommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeamLikeController;
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

    //FOR USER REGSITRY, ADMIN AND USER ACCOUNTS PAGE
    Route::prefix('users')->group(function () {
        Route::post('store/admin', [UserController::class, 'store']);
        Route::delete('delete', [UserController::class, 'delete']);

        Route::prefix('update')->group(function () {
            Route::patch('data', [UserController::class, 'updateData']);
            Route::patch('status', [UserController::class, 'updateStatus']);
            Route::patch('participation', [UserController::class, 'updateParticipationCount']);
        });

        Route::prefix('retrieve')->group(function () {
            Route::get('users', [UserController::class, 'retrieveUsers']);
            Route::get('admins', [UserController::class, 'retrieveAdmins']);
            Route::get('players', [UserController::class, 'retrievePlayers']);
        });

        Route::prefix('voters')->group(function () {
            Route::get('count', [UserController::class, 'getVotersCount']);
        });

        Route::prefix('per-purok')->group(function () {
            Route::get('count', [UserController::class, 'getCountPerPurok']);
        });
    });

    //CATEGORY IS FOR ANNOUNCEMENT AND EVENT
    Route::prefix('category')->group(function () {
        Route::post('store', [CategoryController::class, 'store']);
        Route::patch('update', [CategoryController::class, 'update']);
        Route::delete('delete', [CategoryController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [CategoryController::class, 'retrieveAll']);
            Route::get('paginated', [CategoryController::class, 'retrievePaginate']);
        });
    });

    //FOR ANNOUCEMENT PAGE
    Route::prefix('announcement')->group(function () {
        Route::post('store', [AnnouncementController::class, 'store']);
        Route::post('update', [AnnouncementController::class, 'update']);
        Route::delete('delete', [AnnouncementController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('latest', [AnnouncementController::class, 'retrieveLatest']);
            Route::get('paginated', [AnnouncementController::class, 'retrievePaginate']);
        });
    });

    //FOR EVENT PAGE
    Route::prefix('event')->group(function () {
        Route::post('store', [EventController::class, 'store']);
        Route::patch('update', [EventController::class, 'update']);
        Route::delete('delete', [EventController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [EventController::class, 'retrieveAll']);
            Route::get('paginated', [EventController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('team')->group(function () {
        Route::post('store', [TeamController::class, 'store']);
        Route::patch('update', [TeamController::class, 'update']);
        Route::delete('delete', [TeamController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [TeamController::class, 'retrieveAll']);
            Route::get('paginated', [TeamController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('game')->group(function () {
        Route::post('store', [GameController::class, 'store']);
        Route::delete('delete', [GameController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [GameController::class, 'retrieveAll']);
            Route::get('paginated', [GameController::class, 'retrievePaginate']);
        });

        Route::prefix('update')->group(function () {
            Route::patch('', [GameController::class, 'update']);
            Route::patch('result', [GameController::class, 'updateResult']);
        });
    });

    //FOR SK OFFICIAL PAGE
    Route::prefix('official')->group(function () {
        Route::post('store', [OfficialController::class, 'store']);
        Route::post('update', [OfficialController::class, 'update']);
        Route::delete('delete', [OfficialController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('active', [OfficialController::class, 'retrieveActive']);
            Route::get('paginated', [OfficialController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('position')->group(function () {
        Route::post('store', [PositionController::class, 'store']);
        Route::patch('update', [PositionController::class, 'update']);
        Route::delete('delete', [PositionController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [PositionController::class, 'retrieveAll']);
            Route::get('paginated', [PositionController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('term')->group(function () {
        Route::post('store', [TermController::class, 'store']);
        Route::delete('delete', [TermController::class, 'delete']);
        Route::get('retrieve', [TermController::class, 'retrieve']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [TermController::class, 'retrieveAll']);
            Route::get('paginated', [TermController::class, 'retrievePaginate']);
        });

        Route::prefix('update')->group(function () {
            Route::patch('', [TermController::class, 'update']);
            Route::patch('status', [TermController::class, 'updateStatus']);
        });
    });

    //FOR FORUM PAGE
    Route::prefix('post')->group(function () {
        Route::post('store', [ForumPostController::class, 'store']);
        Route::patch('update', [ForumPostController::class, 'update']);
        Route::delete('delete', [ForumPostController::class, 'delete']);
        Route::get('retrieve', [ForumPostController::class, 'retrieve']);
    });

    Route::prefix('comment')->group(function () {
        Route::post('store', [ForumCommentController::class, 'store']);
        Route::patch('update', [ForumCommentController::class, 'update']);
        Route::delete('delete', [ForumCommentController::class, 'delete']);
        Route::get('retrieve', [ForumCommentController::class, 'retrieve']);
    });

    Route::prefix('like')->group(function () {
        Route::post('', [TeamLikeController::class, 'like']);
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login/guest', [AuthController::class, 'loginAsGuest']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('check', [AuthController::class, 'checkAuth']);
});

Route::prefix('purok')->group(function () {
    Route::get('retrieve/all', [PurokController::class, 'retrieveAll']);
});
