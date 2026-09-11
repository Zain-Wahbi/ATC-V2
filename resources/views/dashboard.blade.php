<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('app.nav_dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900 rounded-2xl shadow-sm p-8 text-white">
            <h3 class="text-2xl font-bold">
                {{ __('app.dashboard_welcome', ['name' => auth()->guard('customer')->user()->first_name]) }} 👋
            </h3>
            <p class="mt-2 text-gray-300">
                {{ __('app.dashboard_subtitle') }}
            </p>

            <div class="mt-6 flex flex-wrap gap-4">
                <x-link-button :href="route('flights.index')" wire:navigate>
                    {{ __('app.browse_flights') }}
                </x-link-button>
                <x-link-secondary :href="route('my-bookings')" wire:navigate class="!bg-transparent !text-white !border-gray-500 hover:!bg-white/10">
                    {{ __('app.nav_bookings') }}
                </x-link-secondary>
            </div>
        </div>
    </div>
</x-app-layout>