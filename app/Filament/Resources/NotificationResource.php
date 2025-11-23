<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Container;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationLabel = 'Notificaciones';
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static ?string $navigationGroup = 'Datos de Sensores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('container_id')
                    ->options(Container::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('notification_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('container.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notification_date')
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}