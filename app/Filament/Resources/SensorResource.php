<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SensorResource\Pages;
use App\Filament\Resources\SensorResource\RelationManagers;
use App\Models\Container;
use App\Models\Sensor;
use App\Models\SensorStatus;
use App\Models\SensorType;
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
                Forms\Components\Select::make('sensor_type_id')
                    ->options(SensorType::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\Select::make('container_id')
                    ->options(Container::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->required(),
                Forms\Components\Select::make('sensor_status_id')
                    ->options(SensorStatus::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('model')
                    ->maxLength(50),
                Forms\Components\DateTimePicker::make('installation_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('last_reading'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sensorType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('container.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sensorStatus.name')
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