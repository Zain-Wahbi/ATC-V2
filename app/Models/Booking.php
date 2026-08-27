<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'customer_id',
        'flight_id',
        'seat_id',
        'total_cost',
        'overweight',
        'booking_date',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Booking $booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'ATC-' . str_pad(
                    (string) (static::max('id') + 1),
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}