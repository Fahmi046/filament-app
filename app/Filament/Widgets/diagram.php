<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class diagram extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'My First Dataset',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,
                ],
            ],
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
