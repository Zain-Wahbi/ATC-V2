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
    public ?int $last_booking_id = null;

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
            'selected_seat_id.required' => __('app.please_select_seat'),
        ]);

        $seat = Seat::find($this->selected_seat_id);

        if (! $seat || $seat->is_booked || $seat->flight_id !== $this->flight->id) {
            $this->addError('selected_seat_id', __('app.seat_unavailable'));
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

        $this->success_message = __('app.booking_confirmed', ['ref' => $booking->booking_reference]);
        $this->last_booking_id = $booking->id;
        $this->selected_seat_id = null;
        $this->overweight = 0;
    }
}; ?>

<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if ($success_message)
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center justify-between flex-wrap gap-3">
                <span>{{ $success_message }}</span>
                <a href="{{ route('bookings.show', $last_booking_id) }}" wire:navigate class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                    {{ __('app.view_ticket') }}
                </a>
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $flight->flight_number }} — {{ $flight->departure_city }} → {{ $flight->destination_city }}
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mb-2">
                <div>
                    <span class="text-gray-500">{{ __('app.departure') }}</span>
                    <p class="font-medium">{{ $flight->departure_time->format('Y-m-d H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('app.base_price') }}</span>
                    <p class="font-medium">${{ number_format($flight->price) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('app.overweight_charge') }}</span>
                    <p class="font-medium">${{ number_format($flight->overweight_charge) }} {{ __('app.per_kg') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('app.status') }}</span>
                    <p class="font-medium">{{ __('app.flight_status_' . $flight->status) }}</p>
                </div>
            </div>
        </div>

        @if (! $success_message)
            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.book_a_seat') }}</h3>

                @if ($this->availableSeats()->isEmpty())
                    <p class="text-sm text-red-600">{{ __('app.no_seats_available') }}</p>
                @else
                    <form wire:submit="book" class="space-y-6">
                        <div>
                            <x-input-label for="selected_seat_id" :value="__('app.select_seat')" />
                            <select wire:model="selected_seat_id" id="selected_seat_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">{{ __('app.choose_a_seat') }}</option>
                                @foreach ($this->availableSeats() as $seat)
                                    <option value="{{ $seat->id }}">{{ $seat->seat_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('selected_seat_id')" />
                        </div>

                        <div>
                            <x-input-label for="overweight" :value="__('app.overweight_kg')" />
                            <x-text-input wire:model.live="overweight" id="overweight" type="number" min="0" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('overweight')" />
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ __('app.total_cost') }}</span>
                            <p class="text-2xl font-bold text-emerald-700">${{ number_format($this->totalCost) }}</p>
                        </div>

                        <x-primary-button>
                            {{ __('app.confirm_booking') }}
                        </x-primary-button>
                    </form>
                @endif
            </div>
        @endif

        <x-link-secondary :href="route('flights.index')" wire:navigate>
            {{ __('app.back_to_flights') }}
        </x-link-secondary>
    </div>
</div>