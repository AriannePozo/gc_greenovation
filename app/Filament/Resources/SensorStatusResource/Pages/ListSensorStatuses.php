<?php

namespace App\Filament\Resources\SensorStatusResource\Pages;

use App\Filament\Resources\SensorStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSensorStatuses extends ListRecords
{
    protected static string $resource = SensorStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
