<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    public function definition(): array
    {
        return [
            "flight_number" => "FL" . $this->faker->unique()->numberBetween(1000, 9999),
            "departure_city" => $this->faker->city(),
            "destination_city" => $this->faker->city(),
            "departure_time" => $this->faker->dateTimeBetween("+1 day", "+30 days"),
            "trip_duration_minutes" => $this->faker->numberBetween(60, 600),
            "seats_count" => $this->faker->numberBetween(50, 300),
            "status" => "upcoming",
            "price" => $this->faker->numberBetween(100, 1000),
            "overweight_charge" => $this->faker->numberBetween(5, 50),
        ];
    }
}