<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Tables;
use App\Models\Seccion;
use Filament\Forms\Form;
use App\Models\Municipio;
use Filament\Tables\Table;
use App\Exports\SeccionsExport;
use App\Imports\SeccionsImport;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SeccionResource\Pages;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class SeccionResource extends Resource
{
    protected static ?string $model = Seccion::class;

    protected static ?string $modelLabel = 'Sección';

    protected static ?string $pluralModelLabel = 'Secciones';

    protected static ?string $navigationLabel = 'Secciones';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'CATALOGOS';

    protected static ?int $navigationSort = 4; 

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('seccion_id', '>', 0)
            ->orderBy('seccion_id');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('seccion_id')
                ->label('N° Sección'),

                Select::make('municipio_id')
                ->label('Municipio')
                ->relationship('municipio', 'nombre')
                ->preload(),

                TextInput::make('numero_ruta')
                ->label('Número Ruta')
                ->numeric(),

                TextInput::make('distrito_fed')
                ->label('Distrito Federal')
                ->numeric(),

                TextInput::make('distrito_loc')
                ->label('Distrito Local')
                ->numeric(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([100, 'all'])
            ->defaultPaginationPageOption(100)
            ->striped()
            ->columns([

                Tables\Columns\TextColumn::make('id')
                ->badge()
                ->sortable(),

                Tables\Columns\TextColumn::make('seccion_id')
                ->size(TextColumnSize::Large)
                ->color('info')
                ->searchable()
                ->sortable(),

                SelectColumn::make('municipio_id')
                    ->label('Municipio')
                    ->options(function () {
                        return Municipio::all()->pluck('nombre', 'id')->toArray();
                    })
                    ->disabled()
                    ->grow(false)
                    ->extraInputAttributes([
                        'style' => 'background-color: #000; color: #fff;',
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\TextColumn::make('numero_ruta')
                ->sortable(),

                Tables\Columns\TextColumn::make('distrito_fed')
                ->sortable(),

                Tables\Columns\TextColumn::make('distrito_loc')
                ->sortable(),

            ])
            ->filters([
                
                SelectFilter::make('municipio_id')
                    ->label('Municipio')
                    ->indicator('Un Municipio')
                    ->options(function () {
                        return DB::table('municipios')
                            ->whereExists(DB::table('seccions')
                                ->select(DB::raw(1))
                                ->whereColumn('seccions.municipio_id', 'municipios.id'))
                            ->pluck('nombre', 'id');
                    })->attribute('municipio_id'),

            ])->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->color('primary')
                    ->size(ActionSize::Medium)
                    ->label('Filtros'),
                )
            
            ->headerActions([

                Action::make('Importar Excel')
                ->color('fiucha')
                ->icon('tabler-file-upload')
                ->modalIcon('tabler-file-upload')
                ->modalSubmitActionLabel('Importar Archivo')
                ->form([
                    FileUpload::make('file')
                    ->label('Archivo')
                    ->required(),
                ])
                ->action(function (array $data) {

                    try {

                        Excel::import(new SeccionsImport, $data['file']);
                        
                    } catch (Exception $e) {
                        
                        SeccionResource::makeNotification(
                            'ERROR AL IMPORTAR ARCHIVO',
                            $e->getMessage(),
                            'danger',
                        )->send();

                        return false;
                                                    
                    }
                }),

                Action::make('Exportar en Excel')
                ->color('primary')
                ->icon('tabler-file-download')
                ->modalIcon('tabler-file-download')
                ->modalSubmitActionLabel('Exportar Archivo')
                ->requiresConfirmation()
                ->action(function () {

                    try {

                        Excel::download(new SeccionsExport, 'export_secciones_prueba_1.xlsx');
                        
                    } catch (Exception $e) {
                        
                        SeccionResource::makeNotification(
                            'ERROR AL EXPORTAR ARCHIVO',
                            $e->getMessage(),
                            'danger',
                        )->send();

                        return false;
                                                    
                    }
                }),

            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ])

            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListSeccions::route('/'),
            'create' => Pages\CreateSeccion::route('/create'),
            'edit' => Pages\EditSeccion::route('/{record}/edit'),
        ];
    }    
        
    private static function makeNotification(string $title, string $body, string $color): Notification
    {
        return Notification::make('PROBLEMAS:')
            ->icon('tabler-face-id-error')
            ->persistent()
            ->color($color)
            ->title($title)
            ->body($body);
    }
 
}
