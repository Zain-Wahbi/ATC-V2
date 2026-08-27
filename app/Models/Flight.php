<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'departure_city',
        'destination_city',
        'departure_time',
        'trip_duration_minutes',
        'seats_count',
        'status',
        'price',
        'overweight_charge',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
        ];
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function availableSeats(): HasMany
    {
        return $this->seats()->where('is_booked', false);
    }
}