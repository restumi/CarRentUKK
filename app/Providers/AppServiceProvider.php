<?php

namespace App\Providers;

use App\Http\Repositories\Car\CarRepository;
use App\Http\Repositories\Car\CarRepositoryInterface;
use App\Http\Repositories\ChatMessage\ChatMessageRepository;
use App\Http\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Http\Repositories\Driver\DriverRepository;
use App\Http\Repositories\Driver\DriverRepositoryInterface;
use App\Http\Repositories\Transaction\TransactionRepository;
use App\Http\Repositories\Transaction\TransactionRepositoryInterface;
use App\Http\Repositories\User\UserRepository;
use App\Http\Repositories\User\UserRepositoryInterface;
use App\Http\Repositories\UserVerification\UserVerificationRepository;
use App\Http\Repositories\UserVerification\UserVerificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
        $this->app->bind(ChatMessageRepositoryInterface::class, ChatMessageRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserVerificationRepositoryInterface::class, UserVerificationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
