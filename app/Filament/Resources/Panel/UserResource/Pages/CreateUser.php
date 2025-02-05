<?php

namespace App\Filament\Resources\Panel\UserResource\Pages;

use App\Filament\Resources\Panel\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
