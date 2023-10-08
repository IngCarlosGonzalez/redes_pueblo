<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Colonia;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ColoniaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ColoniaResource\RelationManagers;

class ColoniaResource extends Resource
{
    protected static ?string $model = Colonia::class;

    protected static ?string $modelLabel = 'Colonias';

    protected static ?string $navigationLabel = 'Colonias';

    protected static ?string $navigationIcon = 'heroicon-m-building-office';

    protected static ?string $navigationGroup = 'CATALOGOS';

    protected static ?int $navigationSort = 2; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos de la Colonia')
                    ->schema([

                        TextInput::make('codigo')
                            ->label('Codigo del INE')
                            ->autofocus()
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(10),

                        TextInput::make('entidad')
                            ->label('Número de Entidad')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(2),

                        Select::make('municipio_id')
                            ->relationship('municipio', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('nombre_mpio')
                            ->label('Nombre de Municipio')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(30),

                        TextInput::make('distrito_fed')
                            ->label('Número Distrito Federal')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(5),

                        TextInput::make('distrito_local')
                            ->label('Número Distrito Local')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(5),

                        TextInput::make('numero_de_ruta')
                            ->label('Número de la Ruta')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(5),
                            
                        TextInput::make('seccion')
                            ->label('Número de Sección')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(5),

                        TextInput::make('tipo_seccion')
                            ->label('Tipo de la Sección')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(20),

                        TextInput::make('tipo_colonia')
                            ->label('Tipo de Colonia')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(30),

                        TextInput::make('nombre_colonia')
                            ->label('Nombre de la Colonia')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(60),

                        TextInput::make('cod_post_colon')
                            ->label('Código Postal')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(5),

                        TextInput::make('num_control')
                            ->label('Número de Control')
                            ->required()
                            ->autocomplete(false)
                            ->maxLength(10),
                            
                        ])
                    ->columns(1)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([20, 50, 100, 'all'])
            ->defaultPaginationPageOption(50)
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('id')
                    ->label('Ident')
                    ->sortable(),
                TextColumn::make('codigo')
                    ->label('CódigoINE')
                    ->sortable(),
                TextColumn::make('entidad')
                    ->label('Entidad')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('municipio_id')
                    ->label('Mpio Id')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('nombre_mpio')
                    ->label('Municipio')
                    ->sortable(),
                TextColumn::make('distrito_fed')
                    ->label('DistritoFed')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('distrito_local')
                    ->label('DistLocal')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('numero_de_ruta')
                    ->label('NúmeroRuta')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('seccion')
                    ->label('NúmSección')
                    ->sortable(),
                TextColumn::make('tipo_seccion')
                    ->label('Tipo Sección')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('tipo_colonia')
                    ->label('Tipo de Colonia')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
                TextColumn::make('nombre_colonia')
                    ->label('Nombre de la Colonia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cod_post_colon')
                    ->label('CodPostal')
                    ->sortable(),
                TextColumn::make('num_control')
                    ->label('NúmControl')
                    ->toggleable(isToggledHiddenByDefault : true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListColonias::route('/'),
            'create' => Pages\CreateColonia::route('/create'),
            'edit' => Pages\EditColonia::route('/{record}/edit'),
        ];
    }    
}
