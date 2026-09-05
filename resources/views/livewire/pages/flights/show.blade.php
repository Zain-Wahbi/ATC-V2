<?php

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Flight $flight;
    public ?int $selected_seat_id = null;
    public int $overweight = 0;
    public ?string $success_message = null;

    public function mount(Flight $flight): void
    {
        $this->flight = $flight;
    }

    public function availableSeats()
    {
        return $this->flight->seats()->where('is_booked', false)->orderBy('seat_number')->get();
    }

    public function getTotalCostProperty(): int
    {
        return $this->flight->price + ($this->overweight * $this->flight->overweight_charge);
    }

    public function book(): void
    {
        $this->validate([
            'selected_seat_id' => ['required', 'exists:seats,id'],
            'overweight' => ['nullable', 'integer', 'min:0'],
        ], [
            'selected_seat_id.required' => 'Please select a seat.',
        ]);

        $seat = Seat::find($this->selected_seat_id);

        if (! $seat || $seat->is_booked || $seat->flight_id !== $this->flight->id) {
            $this->addError('selected_seat_id', 'This seat is no longer available. Please choose another.');
            return;
        }

        $booking = Booking::create([
            'customer_id' => Auth::guard('customer')->id(),
            'flight_id' => $this->flight->id,
            'seat_id' => $seat->id,
            'total_cost' => $this->totalCost,
            'overweight' => $this->overweight,
            'booking_date' => now(),
        ]);

        $seat->update(['is_booked' => true]);

        $this->success_message = "Booking confirmed! Reference: {$booking->booking_reference}";
        $this->selected_seat_id = null;
        $this->overweight = 0;
    }
}; ?>

<div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if ($success_message)
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ $success_message }}
                <a href="{{ route('my-bookings') }}" wire:navigate class="underline font-medium ms-2">
                    {{ __('View My Bookings') }}
                </a>
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ $flight->flight_number }} — {{ $flight->departure_city }} → {{ $flight->destination_city }}
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mb-6">
                <div>
                    <span class="text-gray-500">{{ __('Departure') }}</span>
                    <p class="font-medium">{{ $flight->departure_time->format('Y-m-d H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Base Price') }}</span>
                    <p class="font-medium">${{ number_format($flight->price) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Overweight Charge') }}</span>
                    <p class="font-medium">${{ number_format($flight->overweight_charge) }} / kg</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('Status') }}</span>
                    <p class="font-medium capitalize">{{ $flight->status }}</p>
                </div>
            </div>
        </div>

        @if (! $success_message)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Book a Seat') }}</h3>

                @if ($this->availableSeats()->isEmpty())
                    <p class="text-sm text-red-600">{{ __('No seats available on this flight.') }}</p>
                @else
                    <form wire:submit="book" class="space-y-6">
                        <div>
                            <x-input-label for="selected_seat_id" :value="__('Select Seat')" />
                            <select wire:model="selected_seat_id" id="selected_seat_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">{{ __('-- Choose a seat --') }}</option>
                                @foreach ($this->availableSeats() as $seat)
                                    <option value="{{ $seat->id }}">{{ $seat->seat_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('selected_seat_id')" />
                        </div>

                        <div>
                            <x-input-label for="overweight" :value="__('Overweight (kg)')" />
                            <x-text-input wire:model.live="overweight" id="overweight" type="number" min="0" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('overweight')" />
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ __('Total Cost') }}</span>
                            <p class="text-2xl font-semibold text-gray-900">${{ number_format($this->totalCost) }}</p>
                        </div>

                        <x-primary-button>
                            {{ __('Confirm Booking') }}
                        </x-primary-button>
                    </form>
                @endif
            </div>
        @endif

        <a href="{{ route('flights.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            {{ __('← Back to flights') }}
        </a>
    </div>
</div>