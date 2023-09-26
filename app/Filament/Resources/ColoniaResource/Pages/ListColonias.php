<?php

namespace App\Filament\Resources\ColoniaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ColoniaResource;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;

class ListColonias extends ListRecords
{
    protected static string $resource = ColoniaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ImportAction::make()
                ->fields([
                    ImportField::make('id')
                    ->label('id'),
                    ImportField::make('codigo')
                    ->label('codigo'),
                    ImportField::make('entidad')
                    ->label('entidad'),
                    ImportField::make('municipio_id')
                    ->label('municipio_id'),
                    ImportField::make('nombre_mpio')
                    ->label('nombre_mpio'),
                    ImportField::make('distrito_fed')
                    ->label('distrito_fed'),
                    ImportField::make('distrito_local')
                    ->label('distrito_local'),
                    ImportField::make('numero_de_ruta')
                    ->label('numero_de_ruta'),
                    ImportField::make('seccion')
                    ->label('seccion'),
                    ImportField::make('tipo_seccion')
                    ->label('tipo_seccion'),
                    ImportField::make('tipo_colonia')
                    ->label('tipo_colonia'),
                    ImportField::make('nombre_colonia')
                    ->label('nombre_colonia'),
                    ImportField::make('cod_post_colon')
                    ->label('cod_post_colon'),
                    ImportField::make('num_control')
                    ->label('num_control'),
                    ImportField::make('created_at')
                    ->label('created_at'),
                    ImportField::make('updated_at')
                    ->label('updated_at'),
                ])

        ];
    }

}
