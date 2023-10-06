<?php

namespace App\Filament\Resources\ConvertirResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ConvertirResource;

class EditConvertir extends EditRecord
{
    protected static string $resource = ConvertirResource::class;

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
            ->color('success')
            ->duration(8000)
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->title('CONVERSIÓN COMPLETADA OK')
            ->body('El movimiento de conversión ha sido procesado correctamente.');
    }

}
