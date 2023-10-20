<?php

namespace App\Filament\Resources;

use Exception;
use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use App\Models\Contacto;
use Filament\Forms\Form;
use App\Models\Municipio;
use App\Models\Parametro;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VincularResource\Pages;

class VincularResource extends Resource
{
    protected static ?string $model = Contacto::class;

    protected static ?string $modelLabel = 'Asignable';

    protected static ?string $pluralModelLabel = 'Asignables';

    protected static bool $shouldRegisterNavigation = false;

    public $elejecutor;
    public $elheredero;
    public $nivelenred;

    public function mount()
    {
        $this->elejecutor = auth()->id;
        $parametros = Parametro::query()->where('ejecutor', $this->elejecutor)->first();
        $this->elheredero = $parametros->heredero; 
        $this->nivelenred = $parametros->delnivel;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('owner_id', Auth::user()->id)
            ->where('categoria_id', '<>', 15)
            ->ofNivel(Parametro::query()->where('ejecutor', Auth::user()->id)->first()->delnivel);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([20, 200, 'all'])
            ->defaultPaginationPageOption(20)
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('owner_id')
                    ->label('Owner')
                    ->badge()
                    ->color('info')
                    ->grow(false)
                    ->sortable(),  
                TextColumn::make('id')
                    ->label('IdReg')
                    ->badge()
                    ->color('warning')
                    ->grow(false)
                    ->sortable(),
                SelectColumn::make('municipio_id')
                    ->label('Municipio')
                    ->selectablePlaceholder(false)
                    ->options(Municipio::all()->pluck('nombre', 'id')->toArray())
                    ->disabled()
                    ->grow(false)
                    ->sortable(),
                TextColumn::make('numero_seccion')
                    ->label('Seccion')
                    ->grow(false)
                    ->sortable(),
                TextColumn::make('domicilio_colonia')
                    ->label('Colonia')
                    ->grow(false)
                    ->sortable(),
                TextColumn::make('nombre_en_cadena')
                    ->label('Nombre Persona')
                    ->grow(false)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('municipio_id')
                    ->label('Municipio')
                    ->indicator('Un Municipio')
                    ->options(function () {
                        return DB::table('municipios')
                            ->whereExists(DB::table('contactos')
                                ->select(DB::raw(1))
                                ->whereColumn('contactos.municipio_id', 'municipios.id'))
                            ->pluck('nombre', 'id');
                    })->attribute('municipio_id'),

                SelectFilter::make('numero_seccion')
                    ->label('Sección')
                    ->indicator('Una Sección')
                    ->options(function () {
                        return DB::table('filtrarsecs')
                            ->where('ejecutor', Auth::user()->id)
                            ->orderBy('seccion')
                            ->pluck('descripcion', 'seccion');
                    })->attribute('numero_seccion'),

                SelectFilter::make('colonia_id')
                    ->label('Colonia')
                    ->indicator('Una Colonia')
                    ->options(function () {
                        return DB::table('filtrarcols')
                            ->where('ejecutor', Auth::user()->id)
                            ->orderBy('num_seccion')
                            ->orderBy('nombre_colonia')
                            ->pluck('descripcion', 'colonia_id');
                    })->attribute('colonia_id'),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->persistFiltersInSession(fn (): bool => false)
            ->filtersFormColumns(3)
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->color('primary')
                    ->size(ActionSize::Medium)
                    ->label('Filtros'),
            )

            ->actions([
                //
            ])

            ->bulkActions([
                    BulkAction::make('Asignar Contactos')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                            try {
                                foreach ($records as $registro) {
                                  $llave = $registro->id;
                                  Contacto::Where('id', $llave)->update(['owner_id' => Parametro::query()->where('ejecutor', Auth::user()->id)->first()->heredero]);
                                }
                                $cantidad = $records->count();
                                $elnombre = User::query()->where('id',  Parametro::query()->where('ejecutor', Auth::user()->id)->first()->heredero)->first()->name;
                                VincularResource::makeNotification(
                                    'VINCULACIÓN APLICADA OK',
                                    'Los ' . $cantidad . ' registros seleccionados se vincularon correctamente a: ' . $elnombre,
                                    'success',
                                    'tabler-info-circle-filled',
                                )->send();
                            } catch(Exception $e) {
                                VincularResource::makeNotification(
                                    'ERROR AL APLICAR CAMBIOS',
                                    $e->getMessage(),
                                    'danger',
                                    'tabler-face-id-error',
                                )->send();
                            }
                        }
                    ), 
            ])

            ->emptyStateActions([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVinculars::route('/'), 
            'create' => Pages\CreateVincular::route('/create'),
            'edit'   => Pages\EditVincular::route('/{record}/edit'),
        ];
    }  

    private static function makeNotification(string $title, string $body, string $color, string $iconito): Notification
    {
        return Notification::make('RESULTADOS:')
            ->icon($iconito)
            ->color($color)
            ->title($title)
            ->body($body)
            ->persistent();
    }
    
}
