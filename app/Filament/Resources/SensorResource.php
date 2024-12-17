<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SensorResource\Pages;
use App\Filament\Resources\SensorResource\RelationManagers;
use App\Models\Sensor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SensorResource extends Resource
{
    protected static ?string $model = Sensor::class;
    protected static ?string $navigationLabel = 'Sensores';
    protected static ?string $navigationIcon = 'heroicon-o-rss';
    protected static ?string $navigationGroup = 'Gestion de Sensores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sensor_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('container_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sensor_status_id')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('model')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\DateTimePicker::make('installation_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('last_reading'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sensor_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('container_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sensor_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('installation_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_reading')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSensors::route('/'),
            'create' => Pages\CreateSensor::route('/create'),
            'edit' => Pages\EditSensor::route('/{record}/edit'),
        ];
    }
}
