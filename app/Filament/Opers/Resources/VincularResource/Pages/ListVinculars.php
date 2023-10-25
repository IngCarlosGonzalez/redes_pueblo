<?php

namespace App\Filament\Opers\Resources\VincularResource\Pages;

use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Opers\Resources\VincularResource;
use App\Filament\Opers\Resources\GestionarResource;

class ListVinculars extends ListRecords
{
    protected static string $resource = VincularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('regresar')
                ->size(ActionSize::Large)
                ->icon('tabler-arrow-back-up')
                ->color('fiucha')
                ->action(function () {
                    return redirect(GestionarResource::getUrl());
                }
                )
        ];
    }
    
}
