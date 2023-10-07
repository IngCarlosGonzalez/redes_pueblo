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
use Filament\Tables\Columns\IconColumn;
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
    protected static ?string $modelLabel = 'Organizadores';
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

                                TextInput::make('nombre_en_cadena')
                                ->label('Nombre Completo')
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

                                Section::make('SOLICITUDES AL ADMINISTRADOR')
                                ->description('Dar Click en la Acción por Solicitar')
                                ->schema([

                                    Actions::make([

                                        Action::make('Asignar Usuario')
                                            ->icon('heroicon-m-user-plus')
                                            ->color('info')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($get('tiene_usuario')) {
                                                    if ($get('user_asignado') > 0) {
                                                        $eluser = $get('user_asignado');
                                                        $set('mensaje', 'El organizador ya tiene asignado usuario ' . $eluser . '... No procede. ');
                                                        return true;
                                                    }
                                                }
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    $set('mensaje', 'Procede a registrar la solicitud... Asignar Usuario.');
                                                    // aqui va el codigo

                                                    return true;
                                                } else {
                                                    $set('mensaje', 'Al organizador le falta Teléfono o Correo... No procede.');
                                                    return true;
                                                }
                                            }),

                                        Action::make('Desactivar Usuario')
                                            ->icon('heroicon-m-arrow-down-on-square')
                                            ->color('warning')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if (!$get('tiene_usuario')) {
                                                    $set('mensaje', 'El organizador no tiene usuario de sistema... No procede.');
                                                    return true;
                                                } else {
                                                    if (!$get('user_asignado') > 0) {
                                                        $set('mensaje', 'El organizador no tiene usuario válido... No procede.');
                                                        return true;
                                                    } else {
                                                        $eluser = $get('user_asignado');
                                                        if (!$get('user_vigente')) {
                                                            $set('mensaje', 'El usuario ya está desactivado... No procede.');
                                                            return true;
                                                        } else {
                                                            $set('mensaje', 'El usuario ' . $eluser . ' requiere ser desactivado.');
                                                        }
                                                    }
                                                }
                                                $set('mensaje', 'Procede a registrar la solicitud... Desactivación.');
                                                // aqui va el codigo

                                                return true;
                                            }),

                                        Action::make('Reactivar Usuario')
                                            ->icon('heroicon-m-arrow-up-on-square')
                                            ->color('success')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if (!$get('tiene_usuario')) {
                                                    $set('mensaje', 'El organizador no tiene usuario de sistema... No procede.');
                                                    return true;
                                                } else {
                                                    if (!$get('user_asignado') > 0) {
                                                        $set('mensaje', 'El organizador no tiene usuario válido... No procede.');
                                                        return true;
                                                    } else {
                                                        $eluser = $get('user_asignado');
                                                        if ($get('user_vigente')) {
                                                            $set('mensaje', 'El usuario ya está activado... No procede.');
                                                            return true;
                                                        } else {
                                                            $set('mensaje', 'El usuario ' . $eluser . ' requiere su reactivación.');
                                                        }
                                                    }
                                                }
                                                $set('mensaje', 'Procede a registrar la solicitud... Reactivación');
                                                // aqui va el codigo

                                                return true;
                                            }),

                                        Action::make('Cambiar Password')
                                            ->icon('heroicon-m-key')
                                            ->color('gray')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if (!$get('tiene_usuario')) {
                                                    $set('mensaje', 'El organizador no tiene usuario de sistema... No procede.');
                                                    return true;
                                                } else {
                                                    if (!$get('user_asignado') > 0) {
                                                        $set('mensaje', 'El organizador no tiene usuario válido... No procede.');
                                                        return true;
                                                    } else {
                                                        $eluser = $get('user_asignado');
                                                        if (!$get('user_vigente')) {
                                                            $set('mensaje', 'El usuario está desactivado... No procede.');
                                                            return true;
                                                        } else {
                                                            $set('mensaje', 'El usuario ' . $eluser . ' requiere cambio de password.');
                                                        }
                                                    }
                                                }
                                                $set('mensaje', 'Procede a registrar la solicitud... Nueva Password.');
                                                // aqui va el codigo

                                                return true;
                                            }),

                                    ])->fullWidth(),

                                ])
                                ->columns(1),

                                Section::make('OPERACIONES DE REVERSA')
                                ->aside() 
                                ->description('Para cuando el organizador deja de serlo')
                                ->schema([

                                    Actions::make([

                                        Action::make('Desvincular Contactos')
                                            ->icon('heroicon-m-archive-box-x-mark')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if (!$get('tiene_usuario')) {
                                                    $set('mensaje', 'El organizador no tiene usuario de sistema... No procede.');
                                                    return true;
                                                } else {
                                                    if (!$get('user_asignado') > 0) {
                                                        $set('mensaje', 'El organizador no tiene usuario válido... No procede.');
                                                        return true;
                                                    } else {
                                                        $eluser = $get('user_asignado');
                                                        if ($get('user_vigente')) {
                                                            $set('mensaje', 'El usuario aún está activo... Desactivarlo primero.');
                                                            return true;
                                                        }
                                                    }
                                                }
                                                $hijos = Contacto::where('owner_id', $eluser)->count();
                                                if ($hijos > 0) {
                                                    $set('mensaje', 'Si tiene contactos vinculados... ' . $hijos);
                                                    // aqui va el codigo

                                                    return true;
                                                } else {
                                                    $set('mensaje', 'El usuario no tiene contactos vinculados... No procede.');
                                                    return true;
                                                }
                                            }),

                                        Action::make('Retirar el Nivel')
                                            ->icon('heroicon-m-arrow-long-down')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $set('mensaje', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel > 4) {
                                                    $set('mensaje', 'Nivel menor no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($nivel < 2) {
                                                    $set('mensaje', 'Nivel maaximo no elegible... No procede.');
                                                    return true;
                                                }
                                                if ($get('tiene_usuario')) {
                                                    if ($get('user_asignado') > 0) {
                                                        $eluser = $get('user_asignado');
                                                        if ($get('user_vigente')) {
                                                            $set('mensaje', 'El usuario aún está activo... Desactivarlo primero.');
                                                            return true;
                                                        } else {
                                                            $hijos = Contacto::where('owner_id', $eluser)->count();
                                                            if ($hijos > 0) {
                                                                $set('mensaje', 'No procede pues aún tiene contactos.');
                                                                return true;
                                                            } else {
                                                                $set('mensaje', 'Si se puede regresar a nivel mínimo.');
                                                            }
                                                        }
                                                    }
                                                }
                                                $set('mensaje', 'Procede a regresar a nivel mínimo...');
                                                // aqui va el codigo

                                                return true;
                                            }),
                                    ])->fullWidth(),

                                ])
                                ->columns(1),

                                Section::make()
                                ->schema([
                                    TextInput::make('mensaje')
                                    ->label('M E N S A J E :')
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

                TextColumn::make('id'),

                TextColumn::make('owner_id')
                    ->label('Ownr')
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

                SelectColumn::make('nivel_en_red')
                    ->label('Nivel en Red')
                    ->options([
                        '2' => 'COORD',
                        '3' => 'OPERS',
                        '4' => 'PROMS',
                    ])
                    ->disabled()
                    ->selectablePlaceholder(false),

                IconColumn::make('con_req_admin')
                    ->label('Solicitud?')
                    ->boolean(),

                TextColumn::make('requerimiento')
                    ->label('Requerimiento'),

                IconColumn::make('con_req_listo')
                    ->label('Atendida?')
                    ->boolean(),

                IconColumn::make('tiene_usuario')
                    ->label('Usuario?')
                    ->boolean(),

                TextColumn::make('user_asignado')
                    ->label('Id User')
                    ->badge()
                    ->color('success')
                    ->sortable(),  

                IconColumn::make('user_vigente')
                    ->label('Activo?')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->since(),

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
