<?php

namespace App\Filament\Widgets;

use App\Models\Contacto;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ContactosGrowChart extends ChartWidget
{
    protected static ?string $heading = 'Crecimiento Diario de Contactos';

    protected static ?string $maxHeight = '600px';

    protected static string $color = 'danger';

    protected function getData(): array
    {

        $data = Trend::model(Contacto::class)
        ->between(
            start: now()->subMonth(),
            end: now(),
        )
        ->perDay()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Registros',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];

    }

    protected function getType(): string
    {
        return 'line';
    }
}
