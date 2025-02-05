<?php

namespace App\Filament\Resources\Panel\ParkingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ParkingResource;

class ListParkings extends ListRecords
{
    protected static string $resource = ParkingResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
