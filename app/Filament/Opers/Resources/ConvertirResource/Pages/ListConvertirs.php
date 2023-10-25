<?php

namespace App\Filament\Opers\Resources\ConvertirResource\Pages;

use App\Filament\Opers\Resources\ConvertirResource;
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
