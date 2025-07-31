<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\DriverController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function() {
    Route::middleware('role:admin')->group( function() {
        // ================= ADMIN ROUTE =================
        // ================= CARS =================
        Route::post('/cars', [CarController::class, 'store']);
        Route::patch('/cars/{car}', [CarController::class, 'update']);
        Route::delete('/cars/{car}', [CarController::class, 'destroy']);


        // ================= DRIVERS =================
        Route::post('/drivers', [DriverController::class, 'store']);
        Route::patch('/drivers/{driver}', [DriverController::class, 'update']);
        Route::delete('/drivers/{driver}', [DriverController::class, 'destroy']);
    });

    // ================= USER ROUTE =================
    // ================= CARS =================
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{car}', [CarController::class, 'show']);


    // ================= DRIVERS =================
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::get('/drivers/{driver}', [DriverController::class, 'show']);


    Route::post('/logout', [AuthController::class, 'logout']);
});
