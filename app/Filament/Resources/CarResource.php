<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Car Name'),
                TextInput::make('year')
                    ->numeric()
                    // ->min(now()->subCenturies(3)->year)
                    ->minValue(now()->subCenturies(3)->year)
                    ->required()
                    ->label('Model Year'),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->minLength(1)
                    ->label('Car Price'),
                Select::make('type')
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'hatchback' => 'Hatchback',
                    ])->required()
                    ->label('Car Type'),
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Company Name'),
                        DatePicker::make('founded_at')
                            ->required()
                            ->maxDate(now())
                            ->label('Founded Date'),
                        Select::make('country_id')
                            ->relationship('country', 'name')
                            ->required()
                            ->label('Country')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Country Name'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('year')->sortable(),
                TextColumn::make('price')->sortable(),
                TextColumn::make('type'),
                TextColumn::make('company.name'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'hatchback' => 'Hatchback',
                    ])
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
