<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                    <div class="hidden space-x-8 rtl:space-x-reverse sm:-my-px sm:ms-10 sm:flex">                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('app.nav_dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('flights.index')" :active="request()->routeIs('flights.*')" wire:navigate>
                        {{ __('app.nav_flights') }}
                    </x-nav-link>

                    <x-nav-link :href="route('my-bookings')" :active="request()->routeIs('my-bookings')" wire:navigate>
                        {{ __('app.nav_bookings') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Language Switcher -->
                <div class="flex items-center gap-1" dir="ltr">
                    <a href="{{ route('language.switch', 'en') }}"
                       class="px-2 py-1 text-xs font-bold rounded transition
                              {{ app()->getLocale() === 'en' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:text-emerald-600' }}">
                        EN
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('language.switch', 'ar') }}"
                       class="px-2 py-1 text-xs font-bold rounded transition
                              {{ app()->getLocale() === 'ar' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:text-emerald-600' }}">
                        AR
                    </a>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => Auth::guard('customer')->user()->first_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('app.nav_profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('my-bookings')" wire:navigate>
                            {{ __('app.nav_bookings') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('app.nav_logout') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('app.nav_dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('flights.index')" :active="request()->routeIs('flights.*')" wire:navigate>
                {{ __('app.nav_flights') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('my-bookings')" :active="request()->routeIs('my-bookings')" wire:navigate>
                {{ __('app.nav_bookings') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => Auth::guard('customer')->user()->first_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::guard('customer')->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('app.nav_profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('my-bookings')" wire:navigate>
                    {{ __('app.nav_bookings') }}
                </x-responsive-nav-link>

                <div class="border-t border-gray-200 my-2"></div>

                <!-- Language Switcher -->
                <div class="flex items-center gap-2 px-4 py-2" dir="ltr">
                    <a href="{{ route('language.switch', 'en') }}"
                       class="px-3 py-1 text-sm font-bold rounded transition
                              {{ app()->getLocale() === 'en' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:text-emerald-600' }}">
                        EN
                    </a>
                    <a href="{{ route('language.switch', 'ar') }}"
                       class="px-3 py-1 text-sm font-bold rounded transition
                              {{ app()->getLocale() === 'ar' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:text-emerald-600' }}">
                        AR
                    </a>
                </div>

                <div class="border-t border-gray-200 my-2"></div>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('app.nav_logout') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>