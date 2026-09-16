<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Booking::whereNull('cancelled_at')->sum('total_cost');

        $last7Days = Booking::whereNull('cancelled_at')
            ->where('booking_date', '>=', now()->subDays(7))
            ->sum('total_cost');

        $last30Days = Booking::whereNull('cancelled_at')
            ->where('booking_date', '>=', now()->subDays(30))
            ->sum('total_cost');

        $totalBookings = Booking::whereNull('cancelled_at')->count();

        return [
            Stat::make('Total Revenue', '$' . number_format($totalRevenue))
                ->description('All-time, excluding cancellations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Last 30 Days', '$' . number_format($last30Days))
                ->description('Revenue this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Last 7 Days', '$' . number_format($last7Days))
                ->description('Revenue this week')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Bookings', number_format($totalBookings))
                ->description('Active bookings (non-cancelled)')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('gray'),
        ];
    }
}