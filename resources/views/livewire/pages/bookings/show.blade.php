<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        abort_unless($booking->customer_id === Auth::guard('customer')->id(), 403);

        $this->booking = $booking->load('flight', 'seat', 'customer');
    }

    public function getTicketUrlProperty(): string
    {
        return route('ticket.verify', $this->booking->booking_reference);
    }
}; ?>

<div>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 print:max-w-full">

        <div class="flex items-center justify-between print:hidden">
            <x-link-secondary :href="route('my-bookings')" wire:navigate>
                {{ __('app.back_to_bookings') }}
            </x-link-secondary>

            <button onclick="window.print()" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
                </svg>
                {{ __('app.print_ticket') }}
            </button>
        </div>

        <!-- Boarding Pass Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden print:shadow-none print:border-0">

            <!-- Header -->
            <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 text-white px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-7 h-7 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                    <span class="font-bold text-lg">ATC</span>
                </div>
                <span class="text-sm font-medium text-emerald-300 uppercase tracking-wide">
                    {{ __('app.boarding_pass') }}
                </span>
            </div>

            <div class="flex flex-col sm:flex-row">
                <!-- Main info -->
                <div class="flex-1 p-6 space-y-5">
                    <div>
                        <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.passenger') }}</span>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $booking->customer->first_name }} {{ $booking->customer->last_name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $booking->flight->departure_city }}</p>
                        </div>
                        <div class="flex-1 flex items-center gap-2 text-emerald-500">
                            <span class="h-px flex-1 bg-gray-200"></span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="transform: rotate(90deg)">
                                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            <span class="h-px flex-1 bg-gray-200"></span>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $booking->flight->destination_city }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-2">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.flight') }}</span>
                            <p class="font-semibold text-gray-900">{{ $booking->flight->flight_number }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.seat') }}</span>
                            <p class="font-semibold text-gray-900">{{ $booking->seat->seat_number }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.departure') }}</span>
                            <p class="font-semibold text-gray-900">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.booking_ref') }}</span>
                        <p class="font-mono font-semibold text-emerald-700 text-lg">{{ $booking->booking_reference }}</p>
                    </div>
                </div>

                <!-- Perforated divider -->
                <div class="hidden sm:block w-px border-s-2 border-dashed border-gray-200 my-6"></div>
                <div class="sm:hidden h-px border-t-2 border-dashed border-gray-200 mx-6"></div>

                <!-- QR section -->
                <div class="sm:w-48 p-6 flex flex-col items-center justify-center text-center gap-3">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($this->ticketUrl) }}"
                        alt="QR Code"
                        class="w-32 h-32"
                    >
                    <p class="text-xs text-gray-400">{{ __('app.scan_to_verify') }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            nav, header { display: none !important; }
        }
    </style>
</div>