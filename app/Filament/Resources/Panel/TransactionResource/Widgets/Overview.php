<?php

namespace App\Filament\Resources\Panel\TransactionResource\Widgets;

use App\Filament\Resources\Panel\TransactionResource\Pages\ListTransactions;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Overview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListTransactions::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('All', $this->getPageTableQuery()->sum('amount')),
            Stat::make('Parkings', $this->getPageTableQuery()->where('transactionable_type','App\Models\Booking')->sum('amount')),
            Stat::make('Subscriptions', $this->getPageTableQuery()->where('transactionable_type','!=','App\Models\Booking')->sum('amount')),
        ];
    }
}
