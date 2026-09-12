<?php

use App\Models\Booking;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.blank')] class extends Component
{
    public ?Booking $booking = null;

    public function mount(string $reference): void
    {
        $this->booking = Booking::where('booking_reference', $reference)
            ->with(['flight', 'seat', 'customer'])
            ->first();
    }
}; ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ATC') }} — {{ __('app.boarding_pass') }}</title>

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [dir="rtl"] body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">

            <div class="flex items-center justify-center gap-2 mb-6">
                <svg class="w-8 h-8 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="font-bold text-2xl text-gray-900">ATC</span>
            </div>

            @if (! $booking)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="text-gray-600">{{ __('app.ticket_not_found') }}</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 text-white px-6 py-4 flex items-center justify-between">
                        <span class="text-sm font-medium uppercase tracking-wide text-emerald-300">
                            {{ __('app.boarding_pass') }}
                        </span>
                        @if ($booking->isCancelled())
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-700 text-gray-300">
                                {{ __('app.booking_cancelled_badge') }}
                            </span>
                        @else
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-300">
                                ✓ {{ __('app.valid_ticket') }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.passenger') }}</span>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $booking->customer->first_name }} {{ $booking->customer->last_name }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <p class="text-xl font-bold text-gray-900">{{ $booking->flight->departure_city }}</p>
                            <span class="flex-1 h-px bg-gray-200"></span>
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24" style="transform: rotate(90deg)">
                                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            <span class="flex-1 h-px bg-gray-200"></span>
                            <p class="text-xl font-bold text-gray-900">{{ $booking->flight->destination_city }}</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2 border-t border-gray-100">
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
                                <p class="font-semibold text-gray-900 text-sm">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">{{ __('app.booking_ref') }}</span>
                            <p class="font-mono font-semibold text-emerald-700">{{ $booking->booking_reference }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>