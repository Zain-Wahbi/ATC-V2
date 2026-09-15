<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Bookings';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['customer', 'flight'])
                    ->latest('booking_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_reference')
                    ->label('Reference'),

                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($record) => "{$record->customer->first_name} {$record->customer->last_name}"),

                Tables\Columns\TextColumn::make('flight.flight_number')
                    ->label('Flight'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Amount')
                    ->money(),

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Booked On')
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
            ->defaultPaginationPageOption(5);
    }
}