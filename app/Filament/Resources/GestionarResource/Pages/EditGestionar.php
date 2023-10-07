<?php

namespace App\Filament\Resources\GestionarResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\GestionarResource;

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
            // Actions\DeleteAction::make(),
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
