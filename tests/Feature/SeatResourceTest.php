<?php

use App\Models\Flight;
use App\Models\Seat;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can create a seat linked to a flight', function () {
    $flight = Flight::factory()->create();

    $seat = Seat::create([
        'flight_id' => $flight->id,
        'seat_number' => '1A',
        'is_booked' => false,
    ]);

    expect($seat)->toBeInstanceOf(Seat::class);
    $this->assertDatabaseHas('seats', [
        'seat_number' => '1A',
        'flight_id' => $flight->id,
    ]);
});

it('prevents duplicate seat numbers on the same flight', function () {
    $flight = Flight::factory()->create();

    Seat::create([
        'flight_id' => $flight->id,
        'seat_number' => '1A',
        'is_booked' => false,
    ]);

    expect(fn () => Seat::create([
        'flight_id' => $flight->id,
        'seat_number' => '1A',
        'is_booked' => false,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

it('allows the same seat number on different flights', function () {
    $flight1 = Flight::factory()->create();
    $flight2 = Flight::factory()->create();

    $seat1 = Seat::create([
        'flight_id' => $flight1->id,
        'seat_number' => '1A',
        'is_booked' => false,
    ]);

    $seat2 = Seat::create([
        'flight_id' => $flight2->id,
        'seat_number' => '1A',
        'is_booked' => false,
    ]);

    expect($seat1->seat_number)->toBe($seat2->seat_number);
    expect($seat1->flight_id)->not->toBe($seat2->flight_id);
});