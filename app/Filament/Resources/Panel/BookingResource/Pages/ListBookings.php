<?php

namespace App\Filament\Resources\Panel\BookingResource\Pages;

use App\Filament\Resources\BookingResource\Widgets\Overview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\BookingResource;

class ListBookings extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
           Overview::class
        ];
    
    }
}
