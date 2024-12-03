<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Wallet routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'wallet'], function () {
    Route::get('/balance', [WalletController::class, 'balance']);
    Route::post('/fund', [WalletController::class, 'fund']);
});

// Transactions routes
Route::group(['middleware' => 'auth:api', 'prefix' => 'transactions'], function () {
    Route::post('/airtime', [TransactionController::class, 'airtime']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
