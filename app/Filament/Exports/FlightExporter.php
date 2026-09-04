<?php

namespace App\Filament\Exports;

use App\Models\Flight;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FlightExporter extends Exporter
{
    protected static ?string $model = Flight::class;

    public static function getColumns(): array
{
    return [
        ExportColumn::make('flight_number')
            ->label('Flight Number'),

        ExportColumn::make('departure_city')
            ->label('From'),

        ExportColumn::make('destination_city')
            ->label('To'),

        ExportColumn::make('departure_time')
            ->label('Departure Time'),

        ExportColumn::make('trip_duration_minutes')
            ->label('Duration (minutes)'),

        ExportColumn::make('seats_count')
            ->label('Total Seats'),

        ExportColumn::make('status')
            ->label('Status'),

        ExportColumn::make('price')
            ->label('Price ($)'),

        ExportColumn::make('overweight_charge')
            ->label('Overweight Charge ($)'),
    ];
}

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your flight export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
