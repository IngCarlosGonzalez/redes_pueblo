<?php

namespace App\Filament\Coord\Resources\ConvertirResource\Pages;

use App\Filament\Coord\Resources\ConvertirResource;
use Filament\Resources\Pages\ListRecords;

class ListConvertirs extends ListRecords
{
    protected static string $resource = ConvertirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
