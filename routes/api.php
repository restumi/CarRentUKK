<?php

use App\Classes\ApiResponse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TransactionController;
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


// ================= ADMIN ROUTE =================
Route::middleware('auth:sanctum', 'role:admin')->group(function() {
        // ================= USERS =================
        Route::get('/users', [AuthController::class, 'index']);

        // ================= CARS =================
        Route::post('/cars', [CarController::class, 'store']);
        Route::patch('/cars/{car}', [CarController::class, 'update']);
        Route::delete('/cars/{car}', [CarController::class, 'destroy']);


        // ================= DRIVERS =================
        Route::post('/drivers', [DriverController::class, 'store']);
        Route::patch('/drivers/{driver}', [DriverController::class, 'update']);
        Route::delete('/drivers/{driver}', [DriverController::class, 'destroy']);

        // ================= DRIVERS =================
        Route::get('/admin/transactions', [TransactionController::class, 'adminIndex']);
        Route::post('/admin/transactions/{transaction}/approve', [TransactionController::class, 'approve']);
        Route::post('/admin/transactions/{transaction}/reject', [TransactionController::class, 'reject']);
        Route::post('/admin/transactions/{transaction}/pay', [TransactionController::class, 'updatePaymentStatus']);
        Route::post('/admin/transactions/{transaction}/completed', [TransactionController::class, 'markAsCompleted']);
        Route::post('/admin/transactions/{transaction}/cencel-payment', [TransactionController::class, 'cencelPayment']);
});
