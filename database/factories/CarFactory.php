<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imgPath = storage_path('app/public/cars');
        if(!file_exists($imgPath)){
            mkdir($imgPath, 0777, true);
        }

        return [
            'name' => fake()->name(),
            'brand' => Str::random(5),
            'plate_number' => Str::random(2) . ' 1234 ' . Str::random(1),
            'price_per_day' => random_int(200, 300),
            'description' => 'mobil disewakan',
            'image' => $this->faker->image($imgPath, 640, 480, null, true)
        ];
    }
}
