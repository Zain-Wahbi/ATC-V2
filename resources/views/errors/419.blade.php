<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ATC') }} — 419</title>

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
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 text-white px-4 text-center relative overflow-hidden">

        <!-- Language Switcher -->
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

        <!-- Expired timer icon -->
        <div class="mb-8">
            <svg class="w-20 h-20 text-emerald-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-7xl sm:text-8xl font-bold tracking-wide text-emerald-400">419</h1>

        <h2 class="mt-6 text-2xl sm:text-3xl font-bold leading-relaxed">
            {{ __('app.error_419_title') }}
        </h2>

        <p class="mt-4 text-gray-300 max-w-md leading-loose text-base sm:text-lg">
            {{ __('app.error_419_message') }}
        </p>

        <a href="/" class="mt-10 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition">
            {{ __('app.back_home') }}
        </a>
    </div>
</body>
</html>