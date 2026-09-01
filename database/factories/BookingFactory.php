<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Flight;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            "customer_id" => Customer::factory(),
            "flight_id" => Flight::factory(),
            "seat_id" => Seat::factory(),
            "total_cost" => $this->faker->numberBetween(100, 1000),
            "overweight" => 0,
            "booking_date" => now(),
        ];
    }
}