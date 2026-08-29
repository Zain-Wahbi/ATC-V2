<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth:customer'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth:customer'])
    ->name('profile');

require __DIR__.'/auth.php';