<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class UsersOverview extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Chart';

    protected static ?string $maxHeight = "250px";

    protected int | string | array $columnSpan = 1;

    public function getHeading(): string | Htmlable | null
    {
        return __('Chart');
    }


    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [2433, 3454],
                    "backgroundColor" => [
                        'rgb(255, 205, 86)',
                        'rgb(255, 99, 132)',
                    ],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['yes', 'no'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
