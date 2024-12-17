<?php

namespace App\Filament\Resources\SensorTypeResource\Pages;

use App\Filament\Resources\SensorTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSensorType extends EditRecord
{
    protected static string $resource = SensorTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
