<?php

namespace App\Filament\Widgets;

use App\Models\Seat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SeatOccupancyWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSeats = Seat::count();
        $bookedSeats = Seat::where('is_booked', true)->count();
        $availableSeats = $totalSeats - $bookedSeats;

        $occupancyRate = $totalSeats > 0
            ? round(($bookedSeats / $totalSeats) * 100, 1)
            : 0;

        return [
            Stat::make('Total Seats', $totalSeats)
                ->color('info'),

            Stat::make('Booked Seats', $bookedSeats)
                ->color('danger'),

            Stat::make('Available Seats', $availableSeats)
                ->color('success'),

            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->color($occupancyRate > 70 ? 'danger' : ($occupancyRate > 40 ? 'warning' : 'success')),
        ];
    }
}