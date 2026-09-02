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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-6">
                {{ __('My Bookings') }}
            </h2>

            @if ($this->bookings()->isEmpty())
                <p class="text-sm text-gray-600">{{ __('You have no bookings yet.') }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="px-4 py-3">{{ __('Booking Ref') }}</th>
                                <th class="px-4 py-3">{{ __('Flight') }}</th>
                                <th class="px-4 py-3">{{ __('Route') }}</th>
                                <th class="px-4 py-3">{{ __('Seat') }}</th>
                                <th class="px-4 py-3">{{ __('Departure') }}</th>
                                <th class="px-4 py-3">{{ __('Status') }}</th>
                                <th class="px-4 py-3">{{ __('Total Cost') }}</th>
                                <th class="px-4 py-3">{{ __('Booked On') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($this->bookings() as $booking)
                                <tr class="text-sm text-gray-800">
                                    <td class="px-4 py-3 font-medium">{{ $booking->booking_reference }}</td>
                                    <td class="px-4 py-3">{{ $booking->flight->flight_number }}</td>
                                    <td class="px-4 py-3">
                                        {{ $booking->flight->departure_city }} → {{ $booking->flight->destination_city }}
                                    </td>
                                    <td class="px-4 py-3">{{ $booking->seat->seat_number }}</td>
                                    <td class="px-4 py-3">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-blue-100 text-blue-700' => $booking->flight->status === 'upcoming',
                                            'bg-yellow-100 text-yellow-700' => $booking->flight->status === 'departed',
                                            'bg-green-100 text-green-700' => $booking->flight->status === 'arrived',
                                            'bg-red-100 text-red-700' => $booking->flight->status === 'cancelled',
                                        ])>
                                            {{ ucfirst($booking->flight->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">${{ number_format($booking->total_cost) }}</td>
                                    <td class="px-4 py-3">{{ $booking->booking_date->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>