<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            "passport_number" => $this->faker->unique()->numerify("########"),
            "first_name" => $this->faker->firstName(),
            "father_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "email" => $this->faker->unique()->safeEmail(),
            "phone" => $this->faker->numerify("07########"),
            "dob" => $this->faker->date(),
            "password" => Hash::make("password"),
        ];
    }
}