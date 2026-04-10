<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'check']);

// ================= ADMIN ROUTES =================
Route::prefix('admin')->name('admin.')->group(function () {
    // ================= AUTH =================
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });


    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // ================= USERS =================
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // ================= CHAT MESSAGES =================
        Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{user}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('/chats/{user}/send', [ChatController::class, 'sendMessages'])->name('chats.send');

        // ================= VERIFICATION =================
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', [RegisterController::class, 'index'])->name('index');
            Route::get('/{id}', [RegisterController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [RegisterController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [RegisterController::class, 'reject'])->name('reject');
        });

        // ================= CARS =================
        Route::resource('cars', CarController::class)->except('show');

        // ================= DRIVERS =================
        Route::resource('drivers', DriverController::class)->except('show');

        // ================= TRANSACTIONS =================
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
        Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'updatePaymentStatus'])->name('transactions.pay');
        Route::post('/transactions/{transaction}/completed', [TransactionController::class, 'markAsCompleted'])->name('transactions.completed');
        Route::post('/transactions/{transaction}/cencel-payment', [TransactionController::class, 'cencelPayment'])->name('transactions.cencel-payment');
    });
});
