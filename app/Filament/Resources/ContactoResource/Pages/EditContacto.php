<?php

namespace App\Filament\Resources\ContactoResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ContactoResource;

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
            // Actions\DeleteAction::make(),
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
