<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Flight extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['flight_number', 'departure_city', 'destination_city', 'departure_time', 'status', 'price', 'overweight_charge'])
            ->logOnlyDirty()
            ->useLogName('flight');
    }

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