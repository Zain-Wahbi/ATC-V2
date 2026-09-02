<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth:customer'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth:customer'])
    ->name('profile');

Volt::route('my-bookings', 'pages.my-bookings')
    ->middleware(['auth:customer'])
    ->name('my-bookings');

require __DIR__.'/auth.php';