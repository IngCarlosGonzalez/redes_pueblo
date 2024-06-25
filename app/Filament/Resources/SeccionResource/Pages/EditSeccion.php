<?php

namespace App\Filament\Resources\SeccionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SeccionResource;

class EditSeccion extends EditRecord
{
    protected static string $resource = SeccionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->color('info')
            ->duration(8000)
            ->icon('heroicon-o-check-circle')
            ->iconColor('warning')
            ->title('REGISTRO ALMACENADO OK')
            ->body('El registro ha sido actualizado correctamente.');
    }

}
