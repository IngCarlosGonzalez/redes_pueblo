<?php

namespace App\Filament\Opers\Resources\ContactoResource\Pages;

use App\Filament\Opers\Resources\ContactoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactos extends ListRecords
{
    protected static string $resource = ContactoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
}
