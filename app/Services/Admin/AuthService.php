<?php

namespace App\Services\Admin;

use App\Http\Repositories\Car\CarRepositoryInterface;
use App\Http\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Http\Repositories\Driver\DriverRepositoryInterface;
use App\Http\Repositories\Transaction\TransactionRepositoryInterface;
use App\Http\Repositories\User\UserRepositoryInterface;
use App\Http\Repositories\UserVerification\UserVerificationRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        private CarRepositoryInterface $carRepository,
        private ChatMessageRepositoryInterface $chatMessageRepository,
        private DriverRepositoryInterface $driverRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private UserRepositoryInterface $userRepository,
        private UserVerificationRepositoryInterface $userVerificationRepository
    ){}

    public function login($data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses admin.');
        }

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Admin Panel!');
    }

    public function dashboard()
    {
        $totalUsers = $this->userRepository->all()->count();
        $totalCars = $this->carRepository->all()->count();
        $totalDrivers = $this->driverRepository->all()->count();
        $totalTransactions = $this->transactionRepository->all()->count();

        $pendingVerifications = $this->userVerificationRepository->status('pending')->count();

        $recentTransactions = $this->transactionRepository->recents();

        $totalChats = $this->chatMessageRepository->totalChats();

        return [
            'totalUsers' => $totalUsers,
            'totalCars' => $totalCars,
            'totalDrivers' => $totalDrivers,
            'totalTransactions' => $totalTransactions,
            'pendingVerifications' => $pendingVerifications,
            'recentTransactions' => $recentTransactions,
            'totalChats' => $totalChats
        ];
    }
}
