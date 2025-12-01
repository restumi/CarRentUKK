<?php

use App\Classes\ApiResponse;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\DriverController;
use App\Http\Controllers\Users\TransactionController;
use App\Http\Controllers\Users\UserVerificationController;

Route::get('/', function () {
    return ApiResponse::sendResponse('Kadar Rent Car Local Api', '');
});

Route::post('/register', [UserVerificationController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/status', [UserVerificationController::class, 'getStatus']);
Route::post('midtrans/notifications', [TransactionController::class, 'handleMidtransNotifications']);

Route::middleware('auth:sanctum')->group(function () {
    // ================= CARS =================
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{car}', [CarController::class, 'show']);


    // ================= DRIVERS =================
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::get('/drivers/{driver}', [DriverController::class, 'show']);


    // ================= TRANSACTIONS =================
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::post('/transactions/{transaction}/payment', [TransactionController::class, 'createPayment']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
