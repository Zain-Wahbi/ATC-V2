<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlightResource\Pages;
use App\Filament\Resources\FlightResource\RelationManagers;
use App\Models\Flight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlightResource extends Resource
{
    protected static ?string $model = Flight::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('flight_number')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(20)
                ->helperText('Must be unique, e.g. RJ301'),

            Forms\Components\TextInput::make('departure_city')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('destination_city')
                ->required()
                ->maxLength(100)
                ->different('departure_city')
                ->helperText('Must differ from departure city'),

            Forms\Components\DateTimePicker::make('departure_time')
                ->required()
                ->native(false)
                ->minDate(now())
                ->helperText('Cannot be in the past'),

            Forms\Components\TextInput::make('trip_duration_minutes')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(1440)
                ->suffix('minutes'),

            Forms\Components\TextInput::make('seats_count')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(1000),

            Forms\Components\Select::make('status')
                ->options([
                    'upcoming' => 'Upcoming',
                    'departed' => 'Departed',
                    'arrived' => 'Arrived',
                    'cancelled' => 'Cancelled',
                ])
                ->default('upcoming')
                ->required(),

            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->minValue(1)
                ->prefix('$'),

            Forms\Components\TextInput::make('overweight_charge')
                ->required()
                ->numeric()
                ->minValue(0)
                ->prefix('$'),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flight_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departure_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departure_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trip_duration_minutes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seats_count')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'upcoming' => 'info',
                        'departed' => 'warning',
                        'arrived' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overweight_charge')
                    ->numeric()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlights::route('/'),
            'create' => Pages\CreateFlight::route('/create'),
            'edit' => Pages\EditFlight::route('/{record}/edit'),
        ];
    }
}
