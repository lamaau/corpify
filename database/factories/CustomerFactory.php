<?php

namespace Database\Factories;

use App\Constants\GenderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->generateIndonesianPhoneNumber(),
            'gender' => collect(GenderEnum::cases())->random()->value,
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
        ];
    }

    private function generateIndonesianPhoneNumber(): string
    {
        $prefixes = [
            '+62812',
            '+62813',
            '+62821',
            '+62822',
            '+62823',
            '+62852',
            '+62853',
            '+62851',
            '+62831',
            '+62832',
            '+62833',
            '+62838',
            '+62857',
            '+62858',
            '+62877',
            '+62878',
            '+62879',
        ];

        $prefix = $this->faker->randomElement($prefixes);
        $number = $this->faker->randomNumber(7, true);

        return $prefix . $number;
    }
}
