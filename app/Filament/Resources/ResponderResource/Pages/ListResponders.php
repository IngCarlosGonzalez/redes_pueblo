<?php

namespace App\Filament\Resources\ResponderResource\Pages;

use App\Filament\Resources\ResponderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResponders extends ListRecords
{
    protected static string $resource = ResponderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
