<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Flight;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Demo customer (easy login for testing)
        Customer::firstOrCreate(
            ['email' => 'demo@atc.com'],
            [
                'passport_number' => 'DEMO0001',
                'first_name' => 'Demo',
                'father_name' => 'Test',
                'last_name' => 'User',
                'phone' => '0999000000',
                'dob' => '1995-01-01',
                'password' => Hash::make('password'),
            ]
        );

        // A few more random customers
        Customer::factory(5)->create();

        // Flights with seats
        $routes = [
            ['Damascus', 'Dubai'],
            ['Damascus', 'Istanbul'],
            ['Aleppo', 'Cairo'],
            ['Damascus', 'Beirut'],
            ['Latakia', 'Amman'],
        ];

        foreach ($routes as $i => [$from, $to]) {
            $flight = Flight::create([
                'flight_number' => 'ATC' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'departure_city' => $from,
                'destination_city' => $to,
                'departure_time' => now()->addDays($i + 1)->setTime(8 + $i, 30),
                'trip_duration_minutes' => 90 + ($i * 15),
                'seats_count' => 20,
                'status' => 'upcoming',
                'price' => 100 + ($i * 25),
                'overweight_charge' => 5,
            ]);

            for ($s = 1; $s <= 20; $s++) {
                Seat::create([
                    'flight_id' => $flight->id,
                    'seat_number' => chr(65 + intdiv($s - 1, 4)) . (($s - 1) % 4 + 1),
                    'is_booked' => false,
                ]);
            }
        }
    }
}