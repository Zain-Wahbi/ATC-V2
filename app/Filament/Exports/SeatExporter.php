<?php

namespace App\Filament\Exports;

use App\Models\Seat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SeatExporter extends Exporter
{
    protected static ?string $model = Seat::class;

    public static function getColumns(): array
{
    return [
        ExportColumn::make('flight.flight_number')
            ->label('Flight Number'),

        ExportColumn::make('seat_number')
            ->label('Seat Number'),

        ExportColumn::make('is_booked')
            ->label('Booked'),
    ];
}
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your seat export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
