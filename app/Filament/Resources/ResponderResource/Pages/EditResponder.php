<?php

namespace App\Filament\Resources\ResponderResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ResponderResource;

class EditResponder extends EditRecord
{
    protected static string $resource = ResponderResource::class;

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
            ->title('SOLICITUD ATENDIDA OK')
            ->body('La solicitud de gestión se ha atendido completamente.');
    }

}
