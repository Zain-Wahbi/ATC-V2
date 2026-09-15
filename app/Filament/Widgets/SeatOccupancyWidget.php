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

        $occupancyColor = $occupancyRate > 70 ? 'danger' : ($occupancyRate > 40 ? 'warning' : 'success');

        return [
            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description('Overall seat utilization across all flights')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($occupancyColor),

            Stat::make('Available Seats', number_format($availableSeats))
                ->description('Ready to be sold')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Booked Seats', number_format($bookedSeats))
                ->description('Currently reserved')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('warning'),

            Stat::make('Total Seats', number_format($totalSeats))
                ->description('Capacity across all flights')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('gray'),
        ];
    }
}