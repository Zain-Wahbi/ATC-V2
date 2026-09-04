<?php

namespace App\Filament\Exports;

use App\Models\Booking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BookingExporter extends Exporter
{
    protected static ?string $model = Booking::class;

    public static function getColumns(): array
{
    return [
        ExportColumn::make('booking_reference')
            ->label('Booking Reference'),

        ExportColumn::make('customer.first_name')
            ->label('Customer'),

        ExportColumn::make('flight.flight_number')
            ->label('Flight'),

        ExportColumn::make('seat.seat_number')
            ->label('Seat'),

        ExportColumn::make('total_cost')
            ->label('Total Cost ($)'),

        ExportColumn::make('overweight')
            ->label('Overweight (kg)'),

        ExportColumn::make('booking_date')
            ->label('Booking Date'),
    ];
}

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your booking export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
