<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class TransactionOverview extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Chart';

    protected static ?string $maxHeight = "250px";

    public function getHeading(): string | Htmlable | null
    {
        return __('Chart');
    }

    

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Transaction',
                    'data' => [2433, 3454, 4566, 3300, 5545, 5765, 6787, 8767, 7565, 8576, 9686, 8996],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
