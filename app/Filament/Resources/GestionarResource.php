<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Contacto;
use Filament\Forms\Form;
use App\Models\Parametro;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use App\Forms\Components\Mensajito;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VincularResource;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\GestionarResource\Pages\EditGestionar;
use App\Filament\Resources\GestionarResource\Pages\ListGestionars;
use App\Filament\Resources\GestionarResource\Pages\CreateGestionar;

class GestionarResource extends Resource
{
    protected static ?string $model = Contacto::class;
    protected static ?string $modelLabel = 'Organizador';
    protected static ?string $pluralModelLabel = 'Organizadores';
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
                                    ->disabled()
                                    ->dehydrated(),
    
                                    TextInput::make('clave_tipo')
                                    ->label('Tipo de Contacto')
                                    ->prefixIcon('heroicon-m-user-circle')
                                    ->disabled()
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
                                    ->dehydrated(),

                                    Hidden::make('con_req_admin'),
                                    Hidden::make('requerimiento'),
                                    Hidden::make('con_req_listo'),
                                    Hidden::make('tiene_usuario'),
                                    Hidden::make('user_asignado'),
                                    Hidden::make('user_vigente'),
                                    
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
                                                    $set('con_req_admin', 1);
                                                    $set('requerimiento', 'ASIGNAR-USER');
                                                    $set('con_req_listo', 0);
                                                    $set('mensaje', 'Click en guardar...');
                                                    return true;
                                                } else {
                                                    $set('mensaje', 'Al organizador le falta Teléfono o Correo... No procede.');
                                                    return true;
                                                }
                                            }),

                                        Action::make('Desactivar Usuario')
                                            ->icon('heroicon-m-arrow-down-on-square')
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
                                                $set('con_req_admin', 1);
                                                $set('requerimiento', 'DESACTIVAR-USER');
                                                $set('con_req_listo', 0);
                                                $set('con_req_listo', 0);
                                                $set('mensaje', 'Click en guardar...');
                                                return true;
                                            }),

                                        Action::make('Reactivar Usuario')
                                            ->icon('heroicon-m-arrow-up-on-square')
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
                                                $set('con_req_admin', 1);
                                                $set('requerimiento', 'RE-ACTIVAR-USER');
                                                $set('con_req_listo', 0);
                                                $set('con_req_listo', 0);
                                                $set('mensaje', 'Click en guardar...');
                                                return true;
                                            }),

                                        Action::make('Cambiar Password')
                                            ->icon('heroicon-m-key')
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
                                                $set('con_req_admin', 1);
                                                $set('requerimiento', 'CAMBIAR-PASSORD');
                                                $set('con_req_listo', 0);
                                                $set('con_req_listo', 0);
                                                $set('mensaje', 'Click en guardar...');
                                                return true;
                                            }),

                                    ])->fullWidth(),

                                ])
                                ->columns(1),

                                Section::make('OPERACIONES CON ORGANIZADORES')
                                ->aside() 
                                ->description('Asigna o Desasigna Contactos y en su caso Retira el Nivvel de Organizador...')
                                ->schema([

                                    Actions::make([

                                        Action::make('Vincular Contactos')
                                            ->icon('heroicon-m-plus-circle')
                                            ->color('success')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $ident = $get('id');
                                                $heredero = $get('nombre_en_cadena');
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
                                                            $set('mensaje', 'El usuario no está activo... Reactivarlo primero.');
                                                            return true;
                                                        }
                                                    }
                                                }
                                                // verifica contactos disponibles
                                                $ejecutor  = Auth::user()->id;
                                                $registros = Contacto::where('owner_id', $ejecutor)->where('categoria_id', '<>', 15)->where('nivel_en_red', '>', $nivel)->count();
                                                if (!$registros > 0) {
                                                    $set('mensaje', 'No hay contactos de menor nivel por vincular... No procede.');
                                                    return true;
                                                }

                                                // si hay contactos... prosigue
                                                $set('mensaje', 'Ejecutor: ' . $ejecutor . ' con: ' . $registros . ' disponibles para Id: ' . $eluser);
                                                                                                
                                                // aqui se preparan opciones para filtros.-

                                                DB::table('filtrarsecs')->where('ejecutor', '=', Auth::user()->id)->delete();  
                                                DB::table('filtrarcols')->where('ejecutor', '=', Auth::user()->id)->delete();  
                                             
                                                // primero se concentran secciones y colonias.-

                                                DB::table('filtrarsecs')->insertUsing([
                                                    'ejecutor', 'seccion', 'municipio_id'
                                                ], DB::table('contactos')->select(
                                                    'owner_id', 'numero_seccion', 'municipio_id'
                                                )->where('owner_id', '=', Auth::user()->id)
                                                 ->where('categoria_id', '<>', 15)
                                                 ->where('nivel_en_red', '>', $nivel)
                                                 ->whereNotNull('numero_seccion')
                                                 ->where('numero_seccion', '>', 0)
                                                 ->groupBy('owner_id', 'numero_seccion', 'municipio_id')
                                                );

                                                DB::table('filtrarcols')->insertUsing([
                                                    'ejecutor', 'colonia_id', 'municipio_id', 'num_seccion', 'nombre_colonia'
                                                ], DB::table('contactos')->select(
                                                    'owner_id', 'colonia_id', 'municipio_id', 'numero_seccion', 'domicilio_colonia'
                                                )->where('owner_id', '=', Auth::user()->id)
                                                 ->where('categoria_id', '<>', 15)
                                                 ->where('nivel_en_red', '>', $nivel)
                                                 ->whereNotNull('colonia_id')
                                                 ->where('colonia_id', '>', 0)
                                                 ->groupBy('owner_id', 'colonia_id', 'municipio_id', 'numero_seccion', 'domicilio_colonia')
                                                );
                                             
                                                // luego se cargan nombres de mpios.-

                                                DB::table('municipios')->where('id', '<', 39)
                                                    ->lazyById()->each(function (object $mpio) {
                                                        DB::table('filtrarsecs')
                                                            ->where('ejecutor', Auth::user()->id)
                                                            ->where('municipio_id', $mpio->id)
                                                            ->update(['nombre_mpio' => $mpio->nombre]);
                                                    });
                                                
                                                DB::table('municipios')->where('id', '<', 39)
                                                    ->lazyById()->each(function (object $mpio) {
                                                        DB::table('filtrarcols')
                                                            ->where('ejecutor', Auth::user()->id)
                                                            ->where('municipio_id', $mpio->id)
                                                            ->update(['nombre_mpio' => $mpio->nombre]);
                                                    });
                                             
                                                //y se integran las descripciones.-

                                                DB::table('filtrarsecs')->where('ejecutor', Auth::user()->id)
                                                    ->lazyById()->each(function (object $registro) {
                                                        $cadena = $registro->seccion . ' --- de: ' . $registro->nombre_mpio; 
                                                        DB::table('filtrarsecs')
                                                            ->where('id', $registro->id)
                                                            ->update(['descripcion' => $cadena]);
                                                    });

                                                DB::table('filtrarcols')->where('ejecutor', Auth::user()->id)
                                                    ->lazyById()->each(function (object $registro) {
                                                        $cadena = 'Sección: ' . $registro->num_seccion . ' --- Colonia: ' . $registro->nombre_colonia . ' --- Mpio: ' . $registro->nombre_mpio; 
                                                        DB::table('filtrarcols')
                                                            ->where('id', $registro->id)
                                                            ->update(['descripcion' => $cadena]);
                                                    });

                                                // aqui se notifica.-

                                                GestionarResource::makeNotification(
                                                    'REDIRECCIONAMIENTO OK: CARGANDO OPCIONES...',
                                                    'Se abre listado de contactos para seleccionar y vinculase a:  ' . $heredero,
                                                    'success',
                                                    'tabler-info-circle-filled',
                                                )->send();
                                                
                                                // aqui se arman parametros y redirecciona.-

                                                Parametro::upsert([
                                                    ['ejecutor' => $ejecutor, 'heredero' => $eluser, 'delnivel' => $nivel]
                                                ], ['ejecutor'], ['heredero', 'delnivel']);

                                                //return redirect('admin/vinculars');
                                                return redirect(VincularResource::getUrl());
                                                
                                            }),

                                        Action::make('Desvincular Contactos')
                                            ->icon('heroicon-m-minus-circle')
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
                                                            $set('mensaje', 'El usuario aún está activo: checando contactos....');
                                                            // return true;
                                                        }
                                                    }
                                                }

                                                $hijos = Contacto::where('owner_id', $eluser)->count();

                                                if ($hijos > 0) {
                                                    $ejecutor = Auth::user()->id;
                                                    $set('mensaje', 'Owner: ' . $eluser . ' tiene: ' . $hijos . ' regs. que pasan al Id: ' . $ejecutor);
                                                    // aqui va el codigo
                                                    try {

                                                        Contacto::Where('owner_id', $eluser)->update(['owner_id' => $ejecutor]);

                                                        GestionarResource::makeNotification(
                                                            'DESVINCULACIÓN COMPLETADA OK',
                                                            'Los contactos ahora pertenecen al ejecutor de ésta acción.',
                                                            'success',
                                                            'tabler-info-circle-filled',
                                                        )->send();
    
                                                        return true;
                                                        
                                                    } catch(Exception $e) {

                                                        GestionarResource::makeNotification(
                                                            'ERROR AL APLICAR CAMBIOS',
                                                            $e->getMessage(),
                                                            'danger',
                                                            'tabler-face-id-error',
                                                        )->send();
    
                                                        return false;
                                                        
                                                    }
                                                    $set('mensaje', 'Contactos Desvinculados: Click en guardar...');
                                                    return true;

                                                } else {

                                                    $set('mensaje', 'El organizador no tiene contactos vinculados... No procede.');
                                                    return true;

                                                }
                                            }),

                                        Action::make('Retirar el Nivel')
                                            ->icon('heroicon-m-arrow-long-down')
                                            ->color('naranja')
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
                                                $nuevo = 5;
                                                $catego = 15;
                                                $set('nivel_en_red', $nuevo);
                                                $set('categoria_id', $catego);
                                                $set('clave_tipo', 'Integrante');
                                                $set('mensaje', 'Nivel Retirado: Click en guardar...');
                                                return true;
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

                TextColumn::make('id'),

                TextColumn::make('owner_id')
                    ->label('Ownr')
                    ->badge()
                    ->color('warning'),  

                ImageColumn::make('foto_personal')
                    ->label('Foto')
                    ->disk('digitalocean')
                    ->size(40)
                    ->square(),

                TextColumn::make('nombre_en_cadena')
                    ->label('Nombre Organizador')
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                SelectColumn::make('nivel_en_red')
                    ->label('Nivel en Red')
                    ->options([
                        '2' => 'Cordinad',
                        '3' => 'Operador',
                        '4' => 'Promotor',
                    ])
                    ->disabled()
                    ->extraInputAttributes([
                        'style' => 'background-color: #000; color: #fff;',
                    ])
                    ->selectablePlaceholder(false),

                IconColumn::make('con_req_admin')
                    ->label('Req?')
                    ->boolean(),

                TextColumn::make('requerimiento')
                    ->label('SOLICITUD')
                    ->weight(FontWeight::Bold),

                IconColumn::make('con_req_listo')
                    ->label('Listo?')
                    ->boolean(),

                IconColumn::make('tiene_usuario')
                    ->label('User?')
                    ->boolean(),

                TextColumn::make('user_asignado')
                    ->label('USER')
                    ->color('info')
                    ->size(TextColumnSize::Large),

                IconColumn::make('user_vigente')
                    ->label('Activo?')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->wrap()
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
