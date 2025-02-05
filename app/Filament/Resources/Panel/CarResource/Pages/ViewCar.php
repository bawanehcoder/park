<?php

namespace App\Filament\Resources\Panel\CarResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\CarResource;

class ViewCar extends ViewRecord
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
