<?php

namespace App\Filament\Resources\UserStatusResource\Pages;

use App\Filament\Resources\UserStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserStatuses extends ListRecords
{
    protected static string $resource = UserStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
