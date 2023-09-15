<?php

namespace App\Filament\Resources\ContactoResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ContactoResource;

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
