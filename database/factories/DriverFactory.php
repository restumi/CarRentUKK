<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imgPath = storage_path('app/public/drivers');
        if(!file_exists($imgPath)){
            mkdir($imgPath, 0777, true);
        }

        return [
            'name' => fake()->name(),
            'phone' => random_int(10000000000, 20000000000),
            'age' => random_int(20, 30),
            'photo' => $this->faker->image($imgPath, 640, 480, null, true),
            'address' => Str::random(10),
            'description' => 'driver muda kocak'
        ];
    }
}
