<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    public function definition(): array
    {
        return [
            "flight_id" => Flight::factory(),
            "seat_number" => $this->faker->unique()->bothify("##?"),
            "is_booked" => false,
        ];
    }
}