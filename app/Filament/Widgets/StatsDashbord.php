<?php

namespace App\Filament\Widgets;

use App\Models\faktur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashbord extends BaseWidget
{
    protected function getStats(): array
    {
        $countFaktur = faktur::count();
        return [
            Stat::make('Jumlah Faktur', $countFaktur . ' Faktur'),
            stat::make('nilai', '30%'),
            stat::make('nilai', '30%'),
        ];
    }
}
