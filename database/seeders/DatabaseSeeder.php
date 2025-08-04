<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Driver;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "password" => "password",
            // "password_confirmation" => "password",
            "phone_number" => "08123123123131",
            "address" => "Semarang Utara",
            "role" => "admin"
        ]);

        User::create([
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "password",
            // "password_confirmation" => "password",
            "phone_number" => "081394857643",
            "address" => "Cumi cumi raya",
        ]);

        User::create([
            "name" => "222",
            "email" => "222@gmail.com",
            "password" => "password",
            // "password_confirmation" => "password",
            "phone_number" => "019928374652",
            "address" => "Sumatra Utara",
        ]);

        Car::factory(3)->create();

        Driver::factory(3)->create();
    }
}
