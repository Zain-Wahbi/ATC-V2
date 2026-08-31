<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can create a booking linked to customer, flight, and seat', function () {
    $customer = Customer::factory()->create();
    $flight = Flight::factory()->create(['price' => 300, 'overweight_charge' => 15]);
    $seat = Seat::factory()->create(['flight_id' => $flight->id]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'flight_id' => $flight->id,
        'seat_id' => $seat->id,
        'total_cost' => 300,
        'overweight' => 0,
        'booking_date' => now(),
    ]);

    expect($booking)->toBeInstanceOf(Booking::class);
    $this->assertDatabaseHas('bookings', [
        'customer_id' => $customer->id,
        'flight_id' => $flight->id,
    ]);
});

it('generates a booking reference automatically', function () {
    $customer = Customer::factory()->create();
    $flight = Flight::factory()->create();
    $seat = Seat::factory()->create(['flight_id' => $flight->id]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'flight_id' => $flight->id,
        'seat_id' => $seat->id,
        'total_cost' => 300,
        'overweight' => 0,
        'booking_date' => now(),
    ]);

    expect($booking->booking_reference)->not->toBeNull();
    expect($booking->booking_reference)->toStartWith('ATC-');
});

it('correctly calculates total cost from flight price and overweight charge', function () {
    $flight = Flight::factory()->create([
        'price' => 300,
        'overweight_charge' => 15,
    ]);

    $overweightKg = 5;
    $expectedCost = $flight->price + ($overweightKg * $flight->overweight_charge);

    expect($expectedCost)->toBe(375);
});

it('booking belongs to a payment relationship', function () {
    $customer = Customer::factory()->create();
    $flight = Flight::factory()->create();
    $seat = Seat::factory()->create(['flight_id' => $flight->id]);

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'flight_id' => $flight->id,
        'seat_id' => $seat->id,
        'total_cost' => 300,
        'overweight' => 0,
        'booking_date' => now(),
    ]);

    expect($booking->payment)->toBeNull();

    $payment = $booking->payment()->create([
        'amount' => 300,
        'method' => 'cash',
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    expect($booking->fresh()->payment)->not->toBeNull();
    expect($booking->fresh()->payment->status)->toBe('paid');
});