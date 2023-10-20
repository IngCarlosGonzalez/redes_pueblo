<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Contacto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use App\Forms\Components\Mensajito;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ResponderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\ResponderResource\RelationManagers;
use App\Filament\Resources\ResponderResource\Pages\EditResponder;
use App\Filament\Resources\ResponderResource\Pages\ListResponders;
use App\Filament\Resources\ResponderResource\Pages\CreateResponder;

class ResponderResource extends Resource
{
    protected static ?string $model = Contacto::class;
    protected static ?string $modelLabel = 'Solicitud';
    protected static ?string $pluralModelLabel = 'Solicitudes';
    protected static ?string $navigationLabel = 'Responder';
    protected static ?string $navigationIcon = 'heroicon-m-bolt';
    protected static ?string $navigationGroup = 'CONTACTOS';
    protected static ?int $navigationSort = 4; 

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where([
                ['clave_tipo', 'Organizador'],
                ['con_req_admin', true],
                ['con_req_listo', false]
            ]);
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

                                ])
                                ->compact()
                                ->columns(1),

                                Section::make('Información Relativa')
                                ->schema([

                                    Toggle::make('con_req_admin')
                                    ->label('Con Solicitud')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->disabled()
                                    ->dehydrated(),

                                    TextInput::make('requerimiento')
                                    ->label('Se Solicita')
                                    ->disabled()
                                    ->dehydrated(),

                                    Toggle::make('con_req_listo')
                                    ->label('Atendida OK')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->disabled()
                                    ->dehydrated(),

                                    Toggle::make('tiene_usuario')
                                    ->label('Asignación')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->disabled()
                                    ->dehydrated(),

                                    TextInput::make('user_asignado')
                                    ->label('Id User Asignado')
                                    ->disabled()
                                    ->dehydrated(),

                                    Toggle::make('user_vigente')
                                    ->label('User Vigente')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->disabled()
                                    ->dehydrated(),

                                ])
                                ->compact()
                                ->columns([
                                    'sm' => 1,
                                    'md' => 3,
                                ]),

                                Section::make('ATENCIÓN DE SOLICITUDES')
                                ->description('Dar Click en la Solución por Aplicar...')
                                ->schema([

                                    Actions::make([

                                        Action::make('Asignar Usuario')
                                            ->icon('heroicon-m-user-plus')
                                            ->color('primary')
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

                                                    $username = $get('nombre_en_cadena');
                                                    $usermail = $get('cuenta_de_correo');
                                                    $usercelu = $get('telefono_movil');
                                                    $userpswd = str_ireplace(' ', '', $usercelu);
                                                    $userlevl = $get('nivel_en_red');
                                    
                                                    if ($userlevl == 2) {
                                                        $userrole = 'COORDINADOR';
                                                    } elseif ($userlevl == 3) {
                                                        $userrole = 'OPERADOR';
                                                    } else {
                                                        $userrole = 'PROMOTOR';
                                                    }
                                    
                                                    try {

                                                        $creado = User::create(
                                                            [
                                                                'name'               => $username,
                                                                'email'              => $usermail,
                                                                'password'           => Hash::make($userpswd),
                                                                'email_verified_at'  => null,
                                                                'remember_token'     => Str::random(10),
                                                                'profile_photo_path' => null,
                                                                'is_active'          => true,
                                                                'is_admin'           => false,
                                                                'level_id'           => $userlevl,
                                                            ]
                                                        )->assignRole($userrole);

                                                    } catch(Exception $e) {

                                                        ResponderResource::makeNotification(
                                                            'ERROR AL CREAR REGISTRO',
                                                            $e->getMessage(),
                                                            'danger',
                                                        )->send();
    
                                                        return false;
                                                        
                                                    }
                                    
                                                    if (is_null($creado)) {
                                                        $set('mensaje', 'No se pudo crear nuevo user...');
                                                        return true;
                                                    } else {
                                                        $set('tiene_usuario', true);
                                                        $set('user_asignado', $creado->id);
                                                        $set('user_vigente' , true);
                                                        $set('con_req_admin', false);
                                                        $set('requerimiento', 'user-asignado');
                                                        $set('con_req_listo', true);
                                                        $set('mensaje', 'User ' . $userrole . ' listo: Click en guardar...');
                                                    }
                                                } else {
                                                    $set('mensaje', 'Al organizador le falta Teléfono o Correo... No procede.');
                                                    return true;
                                                }
                                            }),

                                        Action::make('Desactivar Usuario')
                                            ->icon('heroicon-m-arrow-down-on-square')
                                            ->color('primary')
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

                                                $userasig = $get('user_asignado');

                                                try {

                                                    User::Where('id', $userasig)->update((['is_active' => false]));

                                                } catch(Exception $e) {

                                                    ResponderResource::makeNotification(
                                                        'ERROR AL APLICAR CAMBIO',
                                                        $e->getMessage(),
                                                        'danger',
                                                    )->send();

                                                    return false;
                                                    
                                                }

                                                $set('user_vigente' , false);
                                                $set('con_req_admin', false);
                                                $set('requerimiento', 'user-desactivado');
                                                $set('con_req_listo', true);
                                                $set('mensaje', 'User DESACTIVADO: Click en guardar...');
                                                return true;

                                            }),

                                        Action::make('Reactivar Usuario')
                                            ->icon('heroicon-m-arrow-up-on-square')
                                            ->color('primary')
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

                                                $userasig = $get('user_asignado');

                                                try {

                                                    User::Where('id', $userasig)->update((['is_active' => true]));

                                                } catch(Exception $e) {

                                                    ResponderResource::makeNotification(
                                                        'ERROR AL APLICAR CAMBIO',
                                                        $e->getMessage(),
                                                        'danger',
                                                    )->send();

                                                    return false;
                                                    
                                                }

                                                $set('user_vigente' , true);
                                                $set('con_req_admin', false);
                                                $set('requerimiento', 'user-reactivado');
                                                $set('con_req_listo', true);
                                                $set('mensaje', 'User REACTIVADO: Click en guardar...');
                                                return true;

                                            }),

                                        Action::make('Cambiar Password')
                                            ->icon('heroicon-m-key')
                                            ->color('primary')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set, Contacto $record) {
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
                                                
                                                $userasig = $get('user_asignado');
                                                $newpaswd = 'muysecreta';
                                                $userpswd = Hash::make($newpaswd);
                                
                                                try {
                                                    
                                                    User::Where('id', $userasig)->update((['password' => $userpswd]));

                                                } catch(Exception $e) {

                                                    ResponderResource::makeNotification(
                                                        'ERROR AL APLICAR CAMBIO',
                                                        $e->getMessage(),
                                                        'danger',
                                                    )->send();

                                                    return false;
                                                    
                                                }
                                
                                                $set('con_req_admin', false);
                                                $set('requerimiento', 'password-reset');
                                                $set('con_req_listo', true);

                                                $set('mensaje', 'Password Reseteada: Click en guardar...');
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

                TextColumn::make('requerimiento')
                    ->label('SOLICITUD')
                    ->color('fiucha')
                    ->size(TextColumnSize::Large),

                IconColumn::make('tiene_usuario')
                    ->label('User?')
                    ->boolean(),

                TextColumn::make('user_asignado')
                    ->label('Id User')
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
            'index'  => ListResponders::route('/'),
            'create' => CreateResponder::route('/create'),
            'edit'   => EditResponder::route('/{record}/edit'),
        ];
    }   
    
    private static function makeNotification(string $title, string $body, string $color): Notification
    {
        return Notification::make('PROBLEMAS:')
            ->icon('tabler-face-id-error')
            ->duration(9000)
            ->color($color)
            ->title($title)
            ->body($body);
    }
 
}
