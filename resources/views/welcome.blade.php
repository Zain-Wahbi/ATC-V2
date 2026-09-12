<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ATC') }} — Airline Travel Company</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [dir="rtl"] body { font-family: 'Cairo', sans-serif; }
    </style>
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
                <!-- Language Switcher -->
                <div class="flex items-center gap-1" dir="ltr">
                    <a href="{{ route('language.switch', 'en') }}"
                       class="px-2 py-1 text-xs font-bold rounded transition
                              {{ app()->getLocale() === 'en' ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-emerald-400' }}">
                        EN
                    </a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('language.switch', 'ar') }}"
                       class="px-2 py-1 text-xs font-bold rounded transition
                              {{ app()->getLocale() === 'ar' ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-emerald-400' }}">
                        AR
                    </a>
                </div>

                @if (Route::has('login'))
                    @auth('customer')
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-medium hover:text-emerald-400">{{ __('app.nav_dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium hover:text-emerald-400">{{ __('app.log_in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="text-sm font-medium bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg">{{ __('app.register') }}</a>
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
                {!! str_replace(':app', '<span class="text-emerald-400">ATC</span>', __('app.welcome_title')) !!}
            </h1>
            <p class="mt-6 text-lg text-gray-300 max-w-2xl mx-auto">
                {{ __('app.welcome_subtitle') }}
            </p>
            <div class="mt-10 flex justify-center gap-4">
                @auth('customer')
                    <a href="{{ route('flights.index') }}" wire:navigate class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition">
                        {{ __('app.browse_flights') }}
                    </a>
                @else
                    <a href="{{ route('register') }}" wire:navigate class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition">
                        {{ __('app.get_started') }}
                    </a>
                    <a href="{{ route('login') }}" wire:navigate class="border border-gray-400 hover:border-white text-white font-semibold px-8 py-3 rounded-lg transition">
                        {{ __('app.log_in') }}
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
                <h3 class="font-semibold text-lg text-gray-900">{{ __('app.feature_search_title') }}</h3>
                <p class="mt-2 text-sm text-gray-600">{{ __('app.feature_search_desc') }}</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">{{ __('app.feature_book_title') }}</h3>
                <p class="mt-2 text-sm text-gray-600">{{ __('app.feature_book_desc') }}</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">{{ __('app.feature_manage_title') }}</h3>
                <p class="mt-2 text-sm text-gray-600">{{ __('app.feature_manage_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 text-center py-6 text-sm">
        &copy; {{ date('Y') }} {{ __('app.footer_text') }}
    </footer>

</body>
</html>