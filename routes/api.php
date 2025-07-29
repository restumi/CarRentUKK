<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function() {

    // Route admin
    Route::middleware('role:admin')->group( function() {

        Route::get('/admin', function() {
            return response()->json(['message' => 'test admin role']);
        });

    });

    Route::get('/user', function() {
        return response()->json(['message' => 'test user role']);
    });

    //Route user
    Route::post('/logout', [AuthController::class, 'logout']);
});
