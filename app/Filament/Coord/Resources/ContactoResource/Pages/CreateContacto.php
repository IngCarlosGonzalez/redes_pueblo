<?php

namespace App\Filament\Coord\Resources\ContactoResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Coord\Resources\ContactoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContacto extends CreateRecord
{
    protected static string $resource = ContactoResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->color('info')  
            ->duration(8000) 
            ->icon('heroicon-o-check-circle')
            ->iconColor('warning')
            ->title('CONTACTO REGISTRADO OK')
            ->body('El contacto ha sido registrado correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    
}
