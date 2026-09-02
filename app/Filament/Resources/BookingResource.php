<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'first_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('flight_id')
                    ->relationship('flight', 'flight_number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateTotalCost($state, $get('overweight'), $set);
                    }),

                Forms\Components\Select::make('seat_id')
                    ->relationship('seat', 'seat_number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, callable $fail) use ($get) {
                            $seat = \App\Models\Seat::find($value);

                            if (! $seat) {
                                return;
                            }

                            if ($seat->is_booked) {
                                $bookingId = request()->route('record');
                                $existingBooking = \App\Models\Booking::where('seat_id', $seat->id)->first();

                                $belongsToCurrentBooking = $bookingId
                                    && $existingBooking
                                    && $existingBooking->id == $bookingId;

                                if (! $belongsToCurrentBooking) {
                                    $fail('This seat is already booked.');
                                }
                            }
                        };
                    }),

                Forms\Components\TextInput::make('overweight')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix('kg')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateTotalCost($get('flight_id'), $state, $set);
                    }),

                Forms\Components\TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Auto-calculated: flight price + overweight charge'),

                Forms\Components\DateTimePicker::make('booking_date')
                    ->required()
                    ->default(now())
                    ->native(false),
            ]);
    }

    protected static function updateTotalCost($flightId, $overweight, callable $set): void
    {
        if (! $flightId) {
            return;
        }

        $flight = \App\Models\Flight::find($flightId);

        if (! $flight) {
            return;
        }

        $overweightCost = ($overweight ?? 0) * $flight->overweight_charge;
        $set('total_cost', $flight->price + $overweightCost);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flight.flight_number')
                    ->label('Flight')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat.seat_number')
                    ->label('Seat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overweight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}