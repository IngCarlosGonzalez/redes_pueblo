<?php

namespace App\Filament\Coord\Resources\ConvertirResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Coord\Resources\ConvertirResource;
use Filament\Resources\Pages\EditRecord;

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
            //
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
