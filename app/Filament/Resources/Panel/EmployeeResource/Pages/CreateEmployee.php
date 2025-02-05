<?php

namespace App\Filament\Resources\Panel\EmployeeResource\Pages;

use App\Filament\Resources\Panel\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
