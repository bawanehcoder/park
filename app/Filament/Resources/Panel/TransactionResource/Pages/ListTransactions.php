<?php

namespace App\Filament\Resources\Panel\TransactionResource\Pages;

use App\Filament\Resources\Panel\TransactionResource;
use App\Filament\Resources\Panel\TransactionResource\Widgets\Overview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
           Overview::class
        ];
    
    }
}
