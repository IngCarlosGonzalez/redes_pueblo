<?php

namespace App\Filament\Resources\VincularResource\Pages;

use App\Filament\Resources\VincularResource;
use Filament\Resources\Pages\EditRecord;

class EditVincular extends EditRecord
{
    protected static string $resource = VincularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 
        ];
    }
}
