<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DirectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public routes of authentication
Route::controller(UserController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Protected routes of product and logout
Route::middleware('auth:sanctum')->group( function () {
    // USER
    Route::prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function() {
            Route::get('/{id}', 'one_user');
        });
    });

    // AGENCE
    Route::prefix('agence')->group(function () {
        Route::controller(AgenceController::class)->group(function() {
            Route::post('/create', 'register');
            Route::get('/liste', 'all_agences');
            Route::put('/edit', 'update_agence');
            Route::delete('/{id}', 'delete_agence');
        });
    });

    // DIRECTION
    Route::prefix('direction')->group(function () {
        Route::controller(DirectionController::class)->group(function() {
            Route::post('/create', 'register');
            Route::get('/liste', 'all_directions');
        });
    });

    // SERVICE
    Route::prefix('service')->group(function () {
        Route::controller(ServiceController::class)->group(function() {
            Route::post('/create', 'register');
            Route::get('/liste', 'all_services');
            Route::put('/edit', 'update_service');
        });
    });



    Route::post('/logout', [UserController::class, 'logout']);
});
