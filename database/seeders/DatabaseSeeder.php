<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Driver;
use App\Models\UserVerification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Kadar Rent Car',
            'email' => 'admin@kadarrentcar.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User::create([
        //     "name" => "test",
        //     "email" => "test@gmail.com",
        //     "password" => "password",
        // ]);

        UserVerification::factory(10)->create();

        Car::factory(3)->create();

        Driver::factory(3)->create();
    }
}
