<?php

namespace App\Filament\Resources\Panel\ParkingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ParkingResource;

class EditParking extends EditRecord
{
    protected static string $resource = ParkingResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
