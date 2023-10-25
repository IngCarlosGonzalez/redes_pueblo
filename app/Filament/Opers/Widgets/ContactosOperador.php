<?php

namespace App\Filament\Opers\Widgets;

use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactosOperador extends BaseWidget
{
    protected function getStats(): array
    {

        $contTodos = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->count();

        $contInteg = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('clave_tipo', 'Integrante')
        ->count();

        $contOrgan = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('clave_tipo', 'Organizador')
        ->count();

        return [

            Stat::make('Contactos', $contTodos )
            ->description('Total de Contactos Registrados')
            ->descriptionIcon('heroicon-m-globe-americas')
            ->color('success'),
            
            Stat::make('Integrantes', $contInteg )
            ->description('Personas Integrantes en Redes')
            ->descriptionIcon('heroicon-m-puzzle-piece')
            ->color('primary'),
            
            Stat::make('Organizadores', $contOrgan )
            ->description('Colaboradores Nivel Promotor')
            ->descriptionIcon('heroicon-m-user-plus')
            ->color('primary'),
            
        ];
    }
}
