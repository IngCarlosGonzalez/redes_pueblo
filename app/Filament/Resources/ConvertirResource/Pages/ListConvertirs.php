<?php

namespace App\Filament\Resources\ConvertirResource\Pages;

use App\Filament\Resources\ConvertirResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConvertirs extends ListRecords
{
    protected static string $resource = ConvertirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
