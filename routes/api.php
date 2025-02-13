<?php

use App\Http\Controllers\Api\SocietyController;
use App\Http\Controllers\Api\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/validations', [ValidationController::class, 'index']);
    Route::post('/validations/{validation}', [ValidationController::class, 'update']);
});

Route::get('/job-categories', [ValidationController::class, 'job']);



Route::prefix('society')->group(function () {
    Route::post('/register', [SocietyController::class, 'register']);
    Route::post('/login', [SocietyController::class, 'login']);
    Route::post('/logout', [SocietyController::class, 'logout']);

    Route::middleware(['auth:society'])->group(function () {
        Route::post('/validation-request', [ValidationController::class, 'store']);
        Route::get('/validations', [ValidationController::class, 'index']);
    });
});

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
