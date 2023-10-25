<?php

namespace App\Filament\Coord\Widgets;

use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ContactosCoordinador extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $contTodos = Contacto::all()
        ->where('owner_id', Auth::user()->id)
        ->count();

        $contInteg = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('clave_tipo', 'Integrante')->count();

        $contOrgan = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('clave_tipo', 'Organizador')->count();

        $contOpera = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('nivel_en_red', '=', 3)->count();

        $contPromo = Contacto::query()
        ->where('owner_id', Auth::user()->id)
        ->where('nivel_en_red', '=', 4)->count();

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
            ->description('Colaboradores en la Estructura')
            ->descriptionIcon('heroicon-m-user-plus')
            ->color('primary'),
            
            Stat::make('Operadores', $contOpera )
            ->description('Organizadores Nivel Grupo ')
            ->descriptionIcon('heroicon-m-users')
            ->color('info'),
            
            Stat::make('Promotores', $contPromo )
            ->description('Organizadores Nivel Célula')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('info'),

        ];
    }

}
