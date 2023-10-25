<?php

namespace App\Filament\Opers\Resources\GestionarResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Opers\Resources\GestionarResource;
use Filament\Resources\Pages\EditRecord;

class EditGestionar extends EditRecord
{
    protected static string $resource = GestionarResource::class;

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
            ->iconColor('info')
            ->title('GESTIÓN COMPLETADA OK')
            ->body('La operación de gestión se ha ejecutado correctamente.');
    }

}
