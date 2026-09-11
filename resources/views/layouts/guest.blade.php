<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ATC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-800 via-gray-900 to-emerald-900 relative">

        <div class="absolute top-4 end-4 flex items-center gap-1" dir="ltr">
            <a href="{{ route('language.switch', 'en') }}"
               class="px-2 py-1 text-xs font-bold rounded transition
                      {{ app()->getLocale() === 'en' ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:text-white' }}">
                EN
            </a>
            <span class="text-gray-500">|</span>
            <a href="{{ route('language.switch', 'ar') }}"
               class="px-2 py-1 text-xs font-bold rounded transition
                      {{ app()->getLocale() === 'ar' ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:text-white' }}">
                AR
            </a>
        </div>

        <div class="mb-6">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <svg class="w-10 h-10 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2.5 1.5V22l4-1 4 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <span class="font-bold text-3xl text-white">ATC</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>