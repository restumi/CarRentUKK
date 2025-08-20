<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserVerification>
 */
class UserVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facePath = storage_path('app/public/users/face');
        if(!file_exists($facePath)){
            mkdir($facePath, 0777, true);
        }

        $ktpPath = storage_path('app/public/users/ktp');
        if(!file_exists($ktpPath)){
            mkdir($ktpPath, 0777, true);
        }

        $start = 1000000000000000;
        $end = 9999999999999999;

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'nik' => mt_rand($start, $end),
            'phone_number' => '0852' . $this->faker->randomNumber(8),
            'address' => fake()->streetAddress(),
            'ktp_image' => $this->faker->image($ktpPath, 640, 480, null, true),
            'face_image' => $this->faker->image($facePath, 640, 480, null, true),
        ];
    }
}
