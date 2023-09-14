<?php

namespace App\Filament\Resources\ColoniaResource\Pages;

use App\Filament\Resources\ColoniaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListColonias extends ListRecords
{
    protected static string $resource = ColoniaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
