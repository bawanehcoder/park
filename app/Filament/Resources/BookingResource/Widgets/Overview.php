<?php

namespace App\Filament\Resources\BookingResource\Widgets;

use App\Filament\Resources\Panel\BookingResource\Pages\ListBookings;
use App\Models\Booking;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Overview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListBookings::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('All', $this->getPageTableQuery()->count()),
            Stat::make('Current', $this->getPageTableQuery()->where('status','parked')->orWhere('status','confirmed')->count()),
            Stat::make('completed', $this->getPageTableQuery()->where('status','completed')->count()),
            Stat::make('Cancel', $this->getPageTableQuery()->where('status','cancelled')->count()),
            
        ];
    }
}
