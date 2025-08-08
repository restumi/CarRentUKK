<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    // Protected admin routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Cars management
        Route::resource('cars', CarController::class);

        // Drivers management
        Route::resource('drivers', DriverController::class);

        // Transactions management
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
        Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'updatePaymentStatus'])->name('transactions.pay');
        Route::post('/transactions/{transaction}/completed', [TransactionController::class, 'markAsCompleted'])->name('transactions.completed');
        Route::post('/transactions/{transaction}/cencel-payment', [TransactionController::class, 'cencelPayment'])->name('transactions.cencel-payment');
    });
});
