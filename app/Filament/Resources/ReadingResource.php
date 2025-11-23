<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReadingResource\Pages;
use App\Filament\Resources\ReadingResource\RelationManagers;
use App\Models\Reading;
use App\Models\Sensor;
use App\Models\SensorType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReadingResource extends Resource
{
    protected static ?string $model = Reading::class;
    protected static ?string $navigationLabel = 'Lecturas';
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Datos de Sensores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sensor_id')
                    ->options(Sensor::all()->pluck('display_name', 'id')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('reading_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sensor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sensor.sensorType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('container.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reading_date')
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
            'index' => Pages\ListReadings::route('/'),
            'create' => Pages\CreateReading::route('/create'),
            'edit' => Pages\EditReading::route('/{record}/edit'),
        ];
    }
}