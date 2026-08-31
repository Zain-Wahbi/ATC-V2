<?php

use App\Models\Flight;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can create a flight with valid data', function () {
    $flight = Flight::create([
        'flight_number' => 'RJ301',
        'departure_city' => 'Amman',
        'destination_city' => 'Dubai',
        'departure_time' => now()->addDays(2),
        'trip_duration_minutes' => 180,
        'seats_count' => 150,
        'status' => 'upcoming',
        'price' => 300,
        'overweight_charge' => 15,
    ]);

    expect($flight)->toBeInstanceOf(Flight::class);
    $this->assertDatabaseHas('flights', [
        'flight_number' => 'RJ301',
    ]);
});

it('requires a unique flight number', function () {
    Flight::create([
        'flight_number' => 'RJ301',
        'departure_city' => 'Amman',
        'destination_city' => 'Dubai',
        'departure_time' => now()->addDays(2),
        'trip_duration_minutes' => 180,
        'seats_count' => 150,
        'status' => 'upcoming',
        'price' => 300,
        'overweight_charge' => 15,
    ]);

    expect(fn () => Flight::create([
        'flight_number' => 'RJ301',
        'departure_city' => 'Cairo',
        'destination_city' => 'Beirut',
        'departure_time' => now()->addDays(3),
        'trip_duration_minutes' => 120,
        'seats_count' => 100,
        'status' => 'upcoming',
        'price' => 200,
        'overweight_charge' => 10,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

it('has a valid status from the allowed list', function () {
    $flight = Flight::factory()->create(['status' => 'upcoming']);

    expect($flight->status)->toBeIn(['upcoming', 'departed', 'arrived', 'cancelled']);
});