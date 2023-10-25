<?php

namespace App\Filament\Coord\Resources\ContactoResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Coord\Resources\ContactoResource;
use Filament\Resources\Pages\EditRecord;

class EditContacto extends EditRecord
{
    protected static string $resource = ContactoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->color('info')
            ->duration(8000)
            ->icon('heroicon-o-check-circle')
            ->iconColor('warning')
            ->title('CONTACTO ALMACENADO OK')
            ->body('El contacto ha sido actualizado correctamente.');
    }

}
