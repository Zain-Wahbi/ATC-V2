<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $recordTitleAttribute = 'booking_reference';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_reference')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_reference')
                    ->label('Reference'),

                Tables\Columns\TextColumn::make('flight.flight_number')
                    ->label('Flight'),

                Tables\Columns\TextColumn::make('flight.departure_city')
                    ->label('Route')
                    ->formatStateUsing(fn ($record) => "{$record->flight->departure_city} → {$record->flight->destination_city}"),

                Tables\Columns\TextColumn::make('seat.seat_number')
                    ->label('Seat'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking_date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('cancelled_at')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->defaultSort('booking_date', 'desc')
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}