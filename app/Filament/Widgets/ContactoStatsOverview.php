<?php

namespace App\Filament\Widgets;

use App\Models\Contacto;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ContactoStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $contTodos = Contacto::all()->count();
        $contInteg = Contacto::query()->where('clave_tipo', 'Integrante')->count();
        $contOrgan = Contacto::query()->where('clave_tipo', 'Organizador')->count();
        $contCoord = Contacto::query()->where('nivel_en_red', '=', 2)->count();
        $contOpera = Contacto::query()->where('nivel_en_red', '=', 3)->count();
        $contPromo = Contacto::query()->where('nivel_en_red', '=', 4)->count();

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
            
            Stat::make('Coordinadores', $contCoord )
            ->description('Organizadores Nivel División')
            ->descriptionIcon('heroicon-m-user')
            ->color('info'),
            
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
