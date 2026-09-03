<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeatResource\Pages;
use App\Filament\Resources\SeatResource\RelationManagers;
use App\Models\Seat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeatResource extends Resource
{
    protected static ?string $model = Seat::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Flight Operations';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Seat Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('flight_id')
                            ->relationship('flight', 'flight_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('seat_number')
                            ->required()
                            ->maxLength(10)
                            ->helperText('e.g. 12A')
                            ->unique(
                                modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule, callable $get) {
                                    return $rule->where('flight_id', $get('flight_id'));
                                },
                                ignoreRecord: true,
                            ),

                        Forms\Components\Toggle::make('is_booked')
                            ->default(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flight.flight_number')
                    ->label('Flight')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_booked')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('flight_id')
                    ->relationship('flight', 'flight_number')
                    ->label('Flight'),

                Tables\Filters\TernaryFilter::make('is_booked')
                    ->label('Booking Status')
                    ->boolean()
                    ->trueLabel('Booked')
                    ->falseLabel('Available')
                    ->native(false),
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
            'index' => Pages\ListSeats::route('/'),
            'create' => Pages\CreateSeat::route('/create'),
            'edit' => Pages\EditSeat::route('/{record}/edit'),
        ];
    }
}
