<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');

    // Route::post('/register', [RegisteredUserController::class, 'store'])
    //     ->middleware('guest');

    // Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->middleware('guest')
    //     ->name('password.email');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //     ->name('password.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:sanctum');

    // Route::get('/user', [UserController::class, 'show'])
    //     ->middleware('auth:sanctum');
});

Route::apiResource("accounts", AccountController::class)->except(["destroy"]);
Route::apiResource('currencies', CurrencyController::class)->only(['index']);
Route::apiResource("projects", ProjectController::class)->except(["destroy"]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
