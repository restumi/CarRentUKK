<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

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

    public function rndStr()
    {
        $depan = substr(str_shuffle(implode(array_merge(range('A','Z'), range('A','Z')))), 0, 2);
        $tengah = substr(str_shuffle(implode(array_merge(range(0,9), range(0,9)))), 0, 4);
        $belakang = substr(str_shuffle(implode(array_merge(range('A','Z'), range('A','Z')))), 0, 1);

        return $depan . ' ' . $tengah . ' ' . $belakang;
    }

    public function definition(): array
    {
        $imgPath = storage_path('app/public/cars');
        if(!file_exists($imgPath)){
            mkdir($imgPath, 0777, true);
        }

        $plate = CarFactory::rndStr();
        $brand = Arr::random(['Avanza', 'Toyota', 'Wulling']);

        return [
            'name' => fake()->name(),
            'brand' => $brand,
            'plate_number' => $plate,
            'price_per_day' => random_int(200, 300),
            'description' => 'mobil disewakan',
            'image' => $this->faker->image($imgPath, 640, 480, null, true)
        ];
    }
}
