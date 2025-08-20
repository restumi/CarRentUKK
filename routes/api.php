<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\DriverController;
use App\Http\Controllers\Users\TransactionController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Users\UserVerificationController;

Route::post('/register', [UserVerificationController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

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

// Route::middleware('auth:sanctum', 'role:admin')->group(function() {
//     Route::prefix('admin')->name('admin.')->group(function() {

//         Route::get('/verification', [RegisterController::class, 'index'])->name('index');
//         Route::post('/verification/{id}/approve', [RegisterController::class, 'approve'])->name('approve');
//         Route::post('/verification/{id}/reject', [RegisterController::class, 'reject'])->name('reject');
//     });
// });
