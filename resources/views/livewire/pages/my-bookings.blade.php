<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function bookings()
    {
        return Booking::where('customer_id', Auth::guard('customer')->id())
            ->with(['flight', 'seat'])
            ->orderByDesc('booking_date')
            ->get();
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with('flight', 'seat')
            ->firstOrFail();

        if (! $booking->isCancellable()) {
            return;
        }

        $booking->update(['cancelled_at' => now()]);
        $booking->seat->update(['is_booked' => false]);

        $this->dispatch('close');
    }
}; ?>

<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('app.nav_bookings') }}
                </h2>
                <x-link-button :href="route('flights.index')" wire:navigate class="!px-4 !py-2 !text-xs">
                    {{ __('app.book_new_flight') }}
                </x-link-button>
            </div>

            @if ($this->bookings()->isEmpty())
                <x-empty-state
                    icon="ticket"
                    :title="__('app.no_bookings_title')"
                    :description="__('app.no_bookings_desc')"
                >
                    <x-slot:action>
                        <x-link-button :href="route('flights.index')" wire:navigate>
                            {{ __('app.browse_flights') }}
                        </x-link-button>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead>
                            <tr class="text-xs font-medium text-gray-500 uppercase">
                                <th class="px-4 py-3 w-[12%] text-start">{{ __('app.booking_ref') }}</th>
                                <th class="px-4 py-3 w-[8%] text-start">{{ __('app.flight') }}</th>
                                <th class="px-4 py-3 w-[16%] text-start">{{ __('app.route') }}</th>
                                <th class="px-4 py-3 w-[6%] text-start">{{ __('app.seat') }}</th>
                                <th class="px-4 py-3 w-[13%] text-start">{{ __('app.departure') }}</th>
                                <th class="px-4 py-3 w-[9%] text-start">{{ __('app.status') }}</th>
                                <th class="px-4 py-3 w-[9%] text-start">{{ __('app.total_cost') }}</th>
                                <th class="px-4 py-3 w-[11%] text-start">{{ __('app.booked_on') }}</th>
                                <th class="px-4 py-3 w-[16%] text-center">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($this->bookings() as $booking)
                                <tr class="text-sm text-gray-800">
                                    <td class="px-4 py-3 font-medium truncate">{{ $booking->booking_reference }}</td>
                                    <td class="px-4 py-3 truncate">{{ $booking->flight->flight_number }}</td>
                                    <td class="px-4 py-3 truncate">
                                        {{ $booking->flight->departure_city }} → {{ $booking->flight->destination_city }}
                                    </td>
                                    <td class="px-4 py-3 truncate">{{ $booking->seat->seat_number }}</td>
                                    <td class="px-4 py-3 truncate" title="{{ $booking->flight->departure_time->format('Y-m-d H:i') }}">
                                        {{ $booking->flight->departure_time->format('d/m/y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 truncate">
                                        @if ($booking->isCancelled())
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                {{ __('app.booking_cancelled_badge') }}
                                            </span>
                                        @else
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-blue-100 text-blue-700' => $booking->flight->status === 'upcoming',
                                                'bg-yellow-100 text-yellow-700' => $booking->flight->status === 'departed',
                                                'bg-emerald-100 text-emerald-700' => $booking->flight->status === 'arrived',
                                                'bg-red-100 text-red-700' => $booking->flight->status === 'cancelled',
                                            ])>
                                                {{ __('app.flight_status_' . $booking->flight->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 truncate">${{ number_format($booking->total_cost) }}</td>
                                    <td class="px-4 py-3 truncate" title="{{ $booking->booking_date->format('Y-m-d H:i') }}">
                                        {{ $booking->booking_date->format('d/m/y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center gap-2">
                                            
                                                href="{{ route('bookings.show', $booking) }}"
                                                wire:navigate
                                                title="{{ __('app.view_ticket') }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-full text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>

                                            @if ($booking->isCancellable())
                                                <button
                                                    type="button"
                                                    x-data=""
                                                    x-on:click="$dispatch('open-modal', 'cancel-booking-{{ $booking->id }}')"
                                                    title="{{ __('app.cancel_booking') }}"
                                                    class="w-9 h-9 flex items-center justify-center rounded-full text-red-500 bg-red-50 hover:bg-red-100 hover:text-red-600 transition"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9.5 7V5a1.5 1.5 0 011.5-1.5h2A1.5 1.5 0 0114.5 5v2m2 0v12a2 2 0 01-2 2h-5a2 2 0 01-2-2V7h9z" />
                                                    </svg>
                                                </button>

                                                <x-modal name="cancel-booking-{{ $booking->id }}" focusable>
                                                    <div class="p-6">
                                                        <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9.5 7V5a1.5 1.5 0 011.5-1.5h2A1.5 1.5 0 0114.5 5v2m2 0v12a2 2 0 01-2 2h-5a2 2 0 01-2-2V7h9z" />
                                                            </svg>
                                                        </div>

                                                        <h2 class="text-lg font-medium text-gray-900">
                                                            {{ __('app.cancel_booking') }}
                                                        </h2>

                                                        <p class="mt-2 text-sm text-gray-600">
                                                            {{ __('app.cancel_booking_confirm_desc') }}
                                                        </p>

                                                        <div class="mt-6 flex justify-end gap-3">
                                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                                {{ __('app.keep_booking') }}
                                                            </x-secondary-button>

                                                            <x-danger-button wire:click="cancelBooking({{ $booking->id }})">
                                                                {{ __('app.yes_cancel_booking') }}
                                                            </x-danger-button>
                                                        </div>
                                                    </div>
                                                </x-modal>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>