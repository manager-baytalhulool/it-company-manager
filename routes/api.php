<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BimonthlyReceiptController;
use App\Http\Controllers\BimonthlyReportController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MonthlyReceiptController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RepositoryController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    Route::get('accounts/recalculate', [AccountController::class, 'recalculate']);
    Route::get('accounts/set-currencies', [AccountController::class, 'setCurrencies']);
    Route::apiResource("accounts", AccountController::class)->except(["destroy"]);

    Route::apiResource('currencies', CurrencyController::class)->only(['index']);
    Route::apiResource("projects", ProjectController::class);
    Route::apiResource('receipts', ReceiptController::class)->except(['destroy']);
    Route::apiResource('invoices', InvoiceController::class);

    Route::get('backup', [BackupController::class, 'download']);

    Route::prefix('reports')->group(function () {
        Route::get('/monthly', [MonthlyReportController::class, 'index']);
        Route::get('/monthly-receipts', [MonthlyReceiptController::class, 'index']);
        Route::get('/bi-monthly', [BimonthlyReportController::class, 'index']);
        Route::get('/bi-monthly-receipts', [BimonthlyReceiptController::class, 'index']);
    });

    Route::apiResource('repositories', RepositoryController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
