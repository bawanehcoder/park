<?php

namespace App\Filament\Resources\Panel\TransactionResource\Pages;

use App\Filament\Resources\Panel\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
