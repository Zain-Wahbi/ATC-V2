<?php

namespace App\Filament\Widgets;

use App\Models\Flight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingFlightsWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Flights';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Flight::query()
                    ->where('status', 'upcoming')
                    ->where('departure_time', '>=', now())
                    ->orderBy('departure_time')
            )
            ->columns([
                Tables\Columns\TextColumn::make('flight_number')
                    ->label('Flight'),

                Tables\Columns\TextColumn::make('departure_city')
                    ->label('From'),

                Tables\Columns\TextColumn::make('destination_city')
                    ->label('To'),

                Tables\Columns\TextColumn::make('departure_time')
                    ->label('Departs')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('seats_count')
                    ->label('Seats'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultPaginationPageOption(5);
    }
}