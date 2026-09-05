<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'flight_id',
        'seat_number',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'is_booked' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['flight_id', 'seat_number', 'is_booked'])
            ->logOnlyDirty()
            ->useLogName('seat');
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }
}