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
        $this->call([
            AdminSeeder::class,
        ]);

        User::create([
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "password",
        ]);


        Car::factory(3)->create();

        Driver::factory(3)->create();
    }
}
