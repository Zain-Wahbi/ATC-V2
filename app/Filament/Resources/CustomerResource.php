<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'father_name', 'last_name', 'email', 'passport_number'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('passport_number')
                ->required()
                ->maxLength(9)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('father_name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('phone')
                ->tel()
                ->maxLength(15),

            Forms\Components\DatePicker::make('dob'),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn (string $context): bool => $context === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('passport_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record) => "{$record->first_name} {$record->father_name} {$record->last_name}")
                    ->searchable(['first_name', 'father_name', 'last_name']),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone'),

                               Tables\Columns\TextColumn::make('dob')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('loyalty_points')
                    ->label('Loyalty Points')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Bookings')
                    ->counts('bookings')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_bookings')
                    ->label('Has Bookings')
                    ->query(fn ($query) => $query->has('bookings')),

                Tables\Filters\Filter::make('dob')
                    ->form([
                        Forms\Components\DatePicker::make('born_from'),
                        Forms\Components\DatePicker::make('born_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['born_from'], fn ($q, $date) => $q->whereDate('dob', '>=', $date))
                            ->when($data['born_until'], fn ($q, $date) => $q->whereDate('dob', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->hasRole('admin') ?? false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->hasRole('admin') ?? false),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\CustomerResource\RelationManagers\BookingsRelationManager::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}