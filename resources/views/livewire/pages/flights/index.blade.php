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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">
                {{ __('app.available_flights') }}
            </h2>

            <!-- Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div>
                    <x-input-label for="departure_city" :value="__('app.departure_city')" />
                    <x-text-input wire:model.live.debounce.400ms="departure_city" id="departure_city" type="text" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="destination_city" :value="__('app.destination_city')" />
                    <x-text-input wire:model.live.debounce.400ms="destination_city" id="destination_city" type="text" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="date" :value="__('app.date')" />
                    <x-text-input wire:model.live="date" id="date" type="date" class="mt-1 block w-full" />
                </div>

                <div class="flex items-end">
                    <x-secondary-button wire:click="resetFilters">
                        {{ __('app.reset') }}
                    </x-secondary-button>
                </div>
            </div>

            <!-- Flights Table -->
            @if ($this->flights()->isEmpty())
                <x-empty-state
                    icon="search"
                    :title="__('app.no_flights_title')"
                    :description="__('app.no_flights_desc')"
                />
            @else
                                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead>
                            <tr class="text-xs font-medium text-gray-500 uppercase">
                                <th class="px-4 py-3 w-[12%] text-start">{{ __('app.flight') }}</th>
                                <th class="px-4 py-3 w-[28%] text-start">{{ __('app.route') }}</th>
                                <th class="px-4 py-3 w-[20%] text-start">{{ __('app.departure') }}</th>
                                <th class="px-4 py-3 w-[12%] text-start">{{ __('app.price') }}</th>
                                <th class="px-4 py-3 w-[16%] text-start">{{ __('app.available_seats') }}</th>
                                <th class="px-4 py-3 w-[12%] text-start"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($this->flights() as $flight)
                                <tr class="text-sm text-gray-800">
                                    <td class="px-4 py-3 font-medium truncate">{{ $flight->flight_number }}</td>
                                    <td class="px-4 py-3 truncate">{{ $flight->departure_city }} → {{ $flight->destination_city }}</td>
                                    <td class="px-4 py-3 truncate">{{ $flight->departure_time->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3 truncate">${{ number_format($flight->price) }}</td>
                                    <td class="px-4 py-3 truncate">
                                        @if ($flight->available_seats_count > 0)
                                            <span class="text-emerald-700 font-medium">{{ $flight->available_seats_count }} {{ __('app.seats') }}</span>
                                        @else
                                            <span class="text-red-600 font-medium">{{ __('app.full') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <x-link-button :href="route('flights.show', $flight)" wire:navigate class="!px-4 !py-2 !text-xs">
                                            {{ __('app.view_and_book') }}
                                        </x-link-button>
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