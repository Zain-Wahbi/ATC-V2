<?php

use App\Models\Flight;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $departure_city = '';
    public string $destination_city = '';
    public string $date = '';

    public function flights()
    {
        return Flight::query()
            ->where('status', 'upcoming')
            ->when($this->departure_city, fn ($q) => $q->where('departure_city', 'like', "%{$this->departure_city}%"))
            ->when($this->destination_city, fn ($q) => $q->where('destination_city', 'like', "%{$this->destination_city}%"))
            ->when($this->date, fn ($q) => $q->whereDate('departure_time', $this->date))
            ->withCount(['seats as available_seats_count' => fn ($q) => $q->where('is_booked', false)])
            ->orderBy('departure_time')
            ->paginate(10);
    }

    public function resetFilters(): void
    {
        $this->reset('departure_city', 'destination_city', 'date');
    }
}; ?>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-6">
                {{ __('Available Flights') }}
            </h2>

            <!-- Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div>
                    <x-input-label for="departure_city" :value="__('Departure City')" />
                    <x-text-input wire:model.live.debounce.400ms="departure_city" id="departure_city" type="text" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="destination_city" :value="__('Destination City')" />
                    <x-text-input wire:model.live.debounce.400ms="destination_city" id="destination_city" type="text" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="date" :value="__('Date')" />
                    <x-text-input wire:model.live="date" id="date" type="date" class="mt-1 block w-full" />
                </div>

                <div class="flex items-end">
                    <x-secondary-button wire:click="resetFilters">
                        {{ __('Reset') }}
                    </x-secondary-button>
                </div>
            </div>

            <!-- Flights Table -->
            @if ($this->flights()->isEmpty())
                <p class="text-sm text-gray-600">{{ __('No flights found matching your criteria.') }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="px-4 py-3">{{ __('Flight') }}</th>
                                <th class="px-4 py-3">{{ __('Route') }}</th>
                                <th class="px-4 py-3">{{ __('Departure') }}</th>
                                <th class="px-4 py-3">{{ __('Price') }}</th>
                                <th class="px-4 py-3">{{ __('Available Seats') }}</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($this->flights() as $flight)
                                <tr class="text-sm text-gray-800">
                                    <td class="px-4 py-3 font-medium">{{ $flight->flight_number }}</td>
                                    <td class="px-4 py-3">{{ $flight->departure_city }} → {{ $flight->destination_city }}</td>
                                    <td class="px-4 py-3">{{ $flight->departure_time->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">${{ number_format($flight->price) }}</td>
                                    <td class="px-4 py-3">
                                        @if ($flight->available_seats_count > 0)
                                            <span class="text-green-700">{{ $flight->available_seats_count }} {{ __('seats') }}</span>
                                        @else
                                            <span class="text-red-700">{{ __('Full') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('flights.show', $flight) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ __('View & Book') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $this->flights()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>