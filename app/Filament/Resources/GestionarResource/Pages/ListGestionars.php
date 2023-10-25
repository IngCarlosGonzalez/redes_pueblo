<?php

namespace App\Filament\Resources\GestionarResource\Pages;

use App\Filament\Resources\GestionarResource;
use Filament\Resources\Pages\ListRecords;

class ListGestionars extends ListRecords
{
    protected static string $resource = GestionarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
