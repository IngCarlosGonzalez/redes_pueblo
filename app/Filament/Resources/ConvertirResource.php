<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Contacto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Forms\Components\Mensajito;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ConvertirResource\Pages\EditConvertir;
use App\Filament\Resources\ConvertirResource\Pages\ListConvertirs;
use App\Filament\Resources\ConvertirResource\Pages\CreateConvertir;

class ConvertirResource extends Resource
{
    protected static ?string $model = Contacto::class;

    protected static ?string $modelLabel = 'Prospecto';
    protected static ?string $pluralModelLabel = 'Prospectos';

    protected static ?string $navigationLabel = 'Convertir';

    protected static ?string $navigationIcon = 'heroicon-m-adjustments-vertical';

    protected static ?string $navigationGroup = 'CONTACTOS';

    protected static ?int $navigationSort = 2; 

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('categoria_id', '=', 15)
            ->where('nivel_en_red', '=', 5);
    }

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                
                Section::make('DATOS DEL PROSPECTO')

                    ->schema([

                        Section::make()

                            ->schema([

                                Hidden::make('id'),

                                TextInput::make('nombre_en_cadena')
                                ->label('Nombre del Contacto')
                                ->prefixIcon('heroicon-m-user')
                                ->disabled(),

                                Select::make('owner_id')
                                ->label('User Propietario')
                                ->prefixIcon('heroicon-m-star')
                                ->relationship('owner', 'name')
                                ->preload()
                                ->disabled(),

                                Section::make('Datos de Contacto')
                                ->description('Son requeridos para poder crear Usuario')
                                ->aside() 
                                ->schema([

                                    TextInput::make('telefono_movil')
                                    ->label('Teléfono Móvil')
                                    ->prefixIcon('heroicon-m-device-phone-mobile')
                                    ->disabled(),

                                    TextInput::make('cuenta_de_correo')
                                    ->label('Cuenta de Correo')
                                    ->prefixIcon('heroicon-m-envelope')
                                    ->disabled(),

                                ])
                                ->compact()
                                ->columns(1),
                                
                                Select::make('categoria_id')
                                ->label('Categoría Actual')
                                ->prefixIcon('heroicon-m-adjustments-horizontal')
                                ->relationship('categoria', 'nombre')
                                ->preload()
                                ->disabled()
                                ->live()
                                ->dehydrated(),

                                TextInput::make('clave_tipo')
                                ->label('Tipo de Contacto')
                                ->prefixIcon('heroicon-m-user-circle')
                                ->disabled()
                                ->live()
                                ->dehydrated(),

                                Select::make('nivel_en_red')
                                ->label('Nivel en la Red')
                                ->prefixIcon('heroicon-m-swatch')
                                ->options([
                                    '1' => ' 1 - Administrador',
                                    '2' => ' 2 - Coordinador',
                                    '3' => ' 3 - Operador',
                                    '4' => ' 4 - Promotor',
                                    '5' => ' 5 - Pueblo',
                                ])
                                ->disabled()
                                ->live()
                                ->dehydrated(),

                                Section::make('Convertir Contacto')
                                ->description('Darle Nivel de Organizador o Quitarlo')
                                ->aside() 
                                ->schema([

                                    Actions::make([

                                        Action::make('Aumentar el Nivel')
                                            ->icon('heroicon-m-chevron-double-up')
                                            ->color('success')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 3) {
                                                    $set('mensaje', 'Niveles mayores no elegibles');
                                                    return true;
                                                }
                                                if ($nivel == 5) {
                                                    $nuevo = 4;
                                                } elseif ($nivel == 4) {
                                                    $nuevo = 3;
                                                } elseif ($nivel == 3) {
                                                    $nuevo = 2;
                                                } else {
                                                    set('mensaje', '>>> ERROR EN NIVEL DEL REGISTRO <<<');
                                                    return true;
                                                }
                                                $catego = 16;
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('categoria_id', $catego);
                                                    $set('nivel_en_red', $nuevo);
                                                    $set('clave_tipo', 'Organizador');
                                                    $set('mensaje', 'Click en guardar...');
                                                    return true;
                                                } else {
                                                    $set('mensaje', '>>> LE FALTAN MEDIOS DE CONTACTO <<<');
                                                    return true;
                                                }
                                            }),

                                        Action::make('Disminir el Nivel')
                                            ->icon('heroicon-m-chevron-double-down')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel máximo no elegible');
                                                    return true;
                                                }
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel ya es el mínimo');
                                                    return true;
                                                }
                                                if ($nivel == 2) {
                                                    $nuevo = 3;
                                                } elseif ($nivel == 3) {
                                                    $nuevo = 4;
                                                } else {
                                                    $nuevo = 5;
                                                }
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('nivel_en_red', $nuevo);
                                                    $catego = 15;
                                                    if ($nuevo == 5) {
                                                        $set('categoria_id', $catego);
                                                        $set('clave_tipo', 'Integrante');
                                                    }
                                                    $set('mensaje', 'Click en guardar...');
                                                    return true;
                                                } else {
                                                    $set('mensaje', '>>> LE FALTAN MEDIOS DE CONTACTO <<<');
                                                    return true;
                                                }
                                            }),

                                    ])->fullWidth(),

                                ])
                                ->columns(1),

                                Section::make()
                                ->description('Observaciones ó Indicaciones...')
                                ->schema([

                                    Mensajito::make('mensaje')->disabled(),

                                ])
                                ->columns(1),

                            ])

                        ->columns(1),

                    ])    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([100, 'all'])
            ->defaultPaginationPageOption(100)
            ->striped()
            ->columns([

                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('owner_id')
                    ->label('Owner')
                    ->badge()
                    ->color('warning')
                    ->sortable(),  

                ImageColumn::make('foto_personal')
                    ->label('Foto')
                    ->disk('digitalocean')
                    ->size(40)
                    ->circular(),

                TextColumn::make('nombre_en_cadena')
                    ->label('Nombre del Contacto')
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('telefono_movil')
                    ->label('Teléfono Móvil')
                    ->color('success')
                    ->weight(FontWeight::Bold)
                    ->size(TextColumn\TextColumnSize::Medium)
                    ->fontFamily(FontFamily::Mono)
                    ->sortable(),

                TextColumn::make('cuenta_de_correo')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->wrap()
                    ->copyable()
                    ->copyMessage('Dirección de Correo Copiada...')
                    ->copyMessageDuration(2000)
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    /* BulkAction::make('Eliminar')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(), */
                ]),
            ])
            ->emptyStateActions([
                /* Tables\Actions\CreateAction::make()
                    ->createAnother(false), */
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
            'index' => ListConvertirs::route('/'),
            'create' => CreateConvertir::route('/create'),
            'edit' => EditConvertir::route('/{record}/edit'),
        ];
    }    
}
