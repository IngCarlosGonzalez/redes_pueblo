<?php

namespace App\Filament\Resources\SeccionResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SeccionResource;

class CreateSeccion extends CreateRecord
{
    protected static string $resource = SeccionResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->color('info')  
            ->duration(8000) 
            ->icon('heroicon-o-check-circle')
            ->iconColor('warning')
            ->title('SECCIÓN AGREGADA OK')
            ->body('El registro ha sido agregado correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    
}
