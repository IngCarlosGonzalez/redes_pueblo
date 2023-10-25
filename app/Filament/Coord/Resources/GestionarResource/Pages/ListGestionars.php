<?php

namespace App\Filament\Coord\Resources\GestionarResource\Pages;

use App\Filament\Coord\Resources\GestionarResource;
use Filament\Resources\Pages\ListRecords;

class ListGestionars extends ListRecords
{
    protected static string $resource = GestionarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
