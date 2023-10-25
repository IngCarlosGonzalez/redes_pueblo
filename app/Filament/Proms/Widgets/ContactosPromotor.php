<?php

namespace App\Filament\Proms\Widgets;

use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ContactosPromotor extends BaseWidget
{
    protected function getStats(): array
    {
        $contTodos = Contacto::query()->where('owner_id', Auth::user()->id)->count();

        return [
            Stat::make('Contactos', $contTodos )
            ->description('Total de Contactos Registrados')
            ->descriptionIcon('heroicon-m-globe-americas')
            ->color('success'),
        ];
    }
}
