<?php

use App\Classes\ApiResponse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users\CarController;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Users\DriverController;
use App\Http\Controllers\Users\TransactionController;
use App\Models\Transaction;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ================= USER ROUTE =================
Route::middleware('auth:sanctum')->group( function() {
    // ================= CARS =================
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{car}', [CarController::class, 'show']);


    // ================= DRIVERS =================
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::get('/drivers/{driver}', [DriverController::class, 'show']);

    // ================= TRANSACTIONS =================
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);


    Route::post('/logout', [AuthController::class, 'logout']);
}); 
