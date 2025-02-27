<?php

namespace App\Filament\Resources\Panel\ParkingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ParkingResource;

class ViewParking extends ViewRecord
{
    protected static string $resource = ParkingResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
