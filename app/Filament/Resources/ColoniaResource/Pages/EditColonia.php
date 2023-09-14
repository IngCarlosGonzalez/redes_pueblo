<?php

namespace App\Filament\Resources\ColoniaResource\Pages;

use App\Filament\Resources\ColoniaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditColonia extends EditRecord
{
    protected static string $resource = ColoniaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
