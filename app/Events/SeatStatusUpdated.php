<?php

namespace App\Events;

use App\Models\Seat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Seat $seat;

    public function __construct(Seat $seat)
    {
        $this->seat = $seat;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('flight.' . $this->seat->flight_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'seat.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'seat_id' => $this->seat->id,
            'seat_number' => $this->seat->seat_number,
            'is_booked' => $this->seat->is_booked,
        ];
    }
}