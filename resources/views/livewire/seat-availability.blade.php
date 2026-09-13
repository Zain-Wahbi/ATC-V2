<?php

use App\Models\Flight;
use App\Models\Seat;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Flight $flight;
    public $seats;

    public function mount(Flight $flight)
    {
        $this->flight = $flight;
        $this->loadSeats();
    }

    public function loadSeats()
    {
        $this->seats = $this->flight->seats()->orderBy('seat_number')->get();
    }

    #[On('echo:flight.{flight.id},seat.updated')]
    public function seatUpdated($event)
    {
        $this->loadSeats();
    }

    public function with(): array
    {
        return [
            'seats' => $this->seats,
        ];
    }
}; ?>

<div>
    <h2 class="text-xl font-bold mb-4">
        Seat Availability — Flight {{ $flight->flight_number }}
    </h2>

    <div class="grid grid-cols-6 gap-2" wire:poll.visible>
        @foreach ($seats as $seat)
            <div class="p-3 rounded-lg text-center border
                @if ($seat->is_booked)
                    bg-red-100 border-red-400 text-red-700
                @else
                    bg-green-100 border-green-400 text-green-700
                @endif
            ">
                {{ $seat->seat_number }}
                <div class="text-xs">
                    {{ $seat->is_booked ? 'Booked' : 'Available' }}
                </div>
            </div>
        @endforeach
    </div>
</div>