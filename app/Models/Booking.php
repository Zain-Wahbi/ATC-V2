<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['booking_reference', 'customer_id', 'flight_id', 'seat_id', 'total_cost', 'overweight', 'booking_date', 'cancelled_at'])
            ->logOnlyDirty()
            ->useLogName('booking');
    }

    protected $fillable = [
        'booking_reference',
        'customer_id',
        'flight_id',
        'seat_id',
        'total_cost',
        'overweight',
        'booking_date',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'cancelled_at' => 'datetime',
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

    static::created(function (Booking $booking) {
        $pointsEarned = intdiv($booking->total_cost, 10);

        $booking->customer->increment('loyalty_points', $pointsEarned);
    });
}

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    public function isCancellable(): bool
    {
        return ! $this->isCancelled() && $this->flight->status === 'upcoming';
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