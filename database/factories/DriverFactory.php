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
            'age' => random_int(20, 30),
            'gender' => $this->faker->randomElement(['male','female']),
            'status' => $this->faker->randomElement(['available','unavailable']),
            'photo' => $this->faker->image($imgPath, 640, 480, null, true),
            
        ];
    }
}
