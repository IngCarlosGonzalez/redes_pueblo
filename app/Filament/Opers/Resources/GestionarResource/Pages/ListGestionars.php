<?php

namespace App\Filament\Opers\Resources\GestionarResource\Pages;

use App\Filament\Opers\Resources\GestionarResource;
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
