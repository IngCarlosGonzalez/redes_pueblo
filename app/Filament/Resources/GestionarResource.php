<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Contacto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\GestionarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GestionarResource\RelationManagers;
use App\Filament\Resources\GestionarResource\Pages\EditGestionar;
use App\Filament\Resources\GestionarResource\Pages\ListGestionars;
use App\Filament\Resources\GestionarResource\Pages\CreateGestionar;

class GestionarResource extends Resource
{
    protected static ?string $model = Contacto::class;
    protected static ?string $modelLabel = 'Organizador';
    protected static ?string $navigationLabel = 'Gestionar';
    protected static ?string $navigationIcon = 'heroicon-m-cog';
    protected static ?string $navigationGroup = 'CONTACTOS';
    protected static ?int $navigationSort = 3; 

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('clave_tipo', '=', 'Organizador');
    }

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                
                Section::make('DATOS DEL ORGANIZADOR')

                    ->schema([

                        Section::make()

                            ->schema([

                                Hidden::make('id'),

                                TextInput::make('nombre_en_cadena')
                                ->label('Nombre del Organizador')
                                ->prefixIcon('heroicon-m-user')
                                ->disabled(),

                                Select::make('owner_id')
                                ->label('User Propietario')
                                ->prefixIcon('heroicon-m-star')
                                ->relationship('owner', 'name')
                                ->preload()
                                ->disabled(),

                                Section::make('Datos del Registro')
                                ->description('Formas de contacto y claves de control')
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
                                
                                    Select::make('categoria_id')
                                    ->label('Categoría Actual')
                                    ->prefixIcon('heroicon-m-adjustments-horizontal')
                                    ->relationship('categoria', 'nombre')
                                    ->preload()
                                    ->disabled(),
    
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
                                    ->disabled(),
    
                                    TextInput::make('clave_tipo')
                                    ->label('Tipo de Contacto')
                                    ->prefixIcon('heroicon-m-user-circle')
                                    ->disabled(),
    
                                ])
                                ->compact()
                                ->columns(1),

                                Section::make('Gestiones Disponibles')
                                ->description('Seleccionar la Operación por Aplicar')
                                ->schema([

                                    Actions::make([

                                        Action::make('ACCIÖN # 1')
                                            ->icon('heroicon-m-chevron-double-up')
                                            ->color('success')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('requerimiento', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 4) {
                                                    return true;
                                                }
                                                if ($nivel > 2) {
                                                    return true;
                                                }
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('requerimiento', 'Si tiene requisitos');
                                                } else {
                                                    $set('requerimiento', 'No tiene requisitos');
                                                }
                                                // aqui va el codigo
                                            }),

                                        Action::make('ACCIÓN # 2')
                                            ->icon('heroicon-m-chevron-double-down')
                                            ->color('info')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 4) {
                                                    return true;
                                                }
                                                if ($nivel > 2) {
                                                    return true;
                                                }
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('requerimiento', 'Si tiene requisitos');
                                                } else {
                                                    $set('requerimiento', 'No tiene requisitos');
                                                }
                                                // aqui va el codigo
                                            }),

                                        Action::make('ACCIÓN # 3')
                                            ->icon('heroicon-m-chevron-double-left')
                                            ->color('warning')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 4) {
                                                    return true;
                                                }
                                                if ($nivel > 2) {
                                                    return true;
                                                }
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('requerimiento', 'Si tiene requisitos');
                                                } else {
                                                    $set('requerimiento', 'No tiene requisitos');
                                                }
                                                // aqui va el codigo
                                            }),

                                    ])->fullWidth(),

                                ])
                                ->columns(1),

                                Section::make()
                                ->schema([
                                    TextInput::make('requerimiento')
                                    ->label('Atención:')
                                    ->disabled()
                                    ->live()
                                    ->dehydrated(false),
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

                SelectColumn::make('nivel_en_red')
                    ->label('---Nivel')
                    ->options([
                        '2' => 'COORD',
                        '3' => 'OPERS',
                        '4' => 'PROMS',
                    ])
                    ->disabled()
                    ->selectablePlaceholder(false),

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
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'index'  => ListGestionars::route('/'),
            'create' => CreateGestionar::route('/create'),
            'edit'   => EditGestionar::route('/{record}/edit'),
        ];
    }     
}
