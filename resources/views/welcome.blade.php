<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ATC') }} — Airline Travel Company</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- Nav -->
    <nav class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <a href="/" class="flex items-center gap-2">
                <svg class="w-8 h-8 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="font-bold text-xl">ATC</span>
            </a>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth('customer')
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-medium hover:text-emerald-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium hover:text-emerald-400">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="text-sm font-medium bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">
            <h1 class="text-4xl sm:text-6xl font-bold tracking-tight">
                Fly Further with <span class="text-emerald-400">ATC</span>
            </h1>
            <p class="mt-6 text-lg text-gray-300 max-w-2xl mx-auto">
                Browse flights, book your seat, and manage your trips — all in one place.
            </p>
            <div class="mt-10 flex justify-center gap-4">
                @auth('customer')
                    <a href="{{ route('flights.index') }}" wire:navigate class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition">
                        Browse Flights
                    </a>
                @else
                    <a href="{{ route('register') }}" wire:navigate class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" wire:navigate class="border border-gray-400 hover:border-white text-white font-semibold px-8 py-3 rounded-lg transition">
                        Log In
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">Search Flights</h3>
                <p class="mt-2 text-sm text-gray-600">Find the perfect flight by route, date, and price.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">Book Instantly</h3>
                <p class="mt-2 text-sm text-gray-600">Choose your seat and confirm your booking in seconds.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">Manage Bookings</h3>
                <p class="mt-2 text-sm text-gray-600">View and track all your trips from your dashboard.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 text-center py-6 text-sm">
        &copy; {{ date('Y') }} ATC — Airline Travel Company. All rights reserved.
    </footer>

</body>
</html>