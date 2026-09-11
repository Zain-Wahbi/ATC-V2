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
                <p class="text-sm text-gray-600">{{ __('app.no_bookings_yet') }}</p>
            @else
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-start text-xs font-medium text-gray-500 uppercase">
                                <th class="px-4 py-3">{{ __('app.booking_ref') }}</th>
                                <th class="px-4 py-3">{{ __('app.flight') }}</th>
                                <th class="px-4 py-3">{{ __('app.route') }}</th>
                                <th class="px-4 py-3">{{ __('app.seat') }}</th>
                                <th class="px-4 py-3">{{ __('app.departure') }}</th>
                                <th class="px-4 py-3">{{ __('app.status') }}</th>
                                <th class="px-4 py-3">{{ __('app.total_cost') }}</th>
                                <th class="px-4 py-3">{{ __('app.booked_on') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($this->bookings() as $booking)
                                <tr class="text-sm text-gray-800">
                                    <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $booking->booking_reference }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $booking->flight->flight_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $booking->flight->departure_city }} → {{ $booking->flight->destination_city }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $booking->seat->seat_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-blue-100 text-blue-700' => $booking->flight->status === 'upcoming',
                                            'bg-yellow-100 text-yellow-700' => $booking->flight->status === 'departed',
                                            'bg-emerald-100 text-emerald-700' => $booking->flight->status === 'arrived',
                                            'bg-red-100 text-red-700' => $booking->flight->status === 'cancelled',
                                        ])>
                                            {{ __('app.flight_status_' . $booking->flight->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">${{ number_format($booking->total_cost) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $booking->booking_date->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>