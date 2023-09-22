<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Colonia;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Contacto;
use Filament\Forms\Form;
use App\Models\Municipio;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ContactoResource\RelationManagers;

class ContactoResource extends Resource
{
    protected static ?string $model = Contacto::class;

    protected static ?string $modelLabel = 'Contactos';

    protected static ?string $navigationLabel = 'Contactos';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'REGISTRO';

    protected static ?int $navigationSort = 1; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('DATOS DEL CONTACTO')
                    ->schema([

                        Section::make()
                        ->schema([

                            TextInput::make('nombre_completo')
                            ->label('Nombre Completo')
                            ->autofocus(fn (): bool => true)
                            ->required()
                            ->autocomplete(false)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->maxLength(60),
                            
                            Select::make('clave_genero')
                            ->options([
                                'FEMENINO'   => 'FEMENINO',
                                'MASCULINO'  => 'MASCULINO',
                                'DIVERSIDAD' => 'DIVERSIDAD',
                                'SIN DATOS'  => 'SIN DATOS',
                            ])
                            ->required(),

                            DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->format('Y/m/d')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->subYears(100))
                            ->maxDate(now())
                            ->required(),

                            TextInput::make('dato_de_curp')
                            ->label('Teclear la CURP')
                            ->maxLength(20)
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            Select::make('clave_origen')
                            ->options([
                                'OFICINAS'    => 'OFICINAS',
                                'BRIGADEO'    => 'BRIGADEO',
                                'REFERENCIAS' => 'REFERENCIAS',
                                'IMPORTADOS'  => 'IMPORTADOS',
                                'OTROS'       => 'OTROS',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('owner_id', auth()->user()->id)),

                            Hidden::make('owner_id')
                            ->live(),

                            Select::make('categoria_id')
                            ->relationship('categoria', 'nombre')
                            ->preload()
                            ->required(),
                
                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos del Domicilio')
                        ->description('Ubicación del domicilio particular del Contacto')
                        ->aside() 
                        ->schema([
                            
                            Select::make('municipio_id')
                            ->label('Municipio')
                            ->options(Municipio::all()->pluck('nombre', 'id')->toArray())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('colonia_id', null)),

                            Select::make('colonia_id')
                            ->label('Colonia')
                            ->searchable()
                            ->options(function (Get $get) {
                                $mpio = Municipio::find($get('municipio_id'));
                                if($mpio){
                                    return $mpio->colonias->pluck('nombre_colonia', 'id');
                                }
                            })
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $set('colonia_catalogada', true);
                                $set('domicilio_colonia', 'n/a');
                                $set('domicilio_codpost', '-----');
                                $set('con_domi_actual', 0);
                                $set('distrito_federal', 0);
                                $set('distrito_estatal', 0);
                                $set('numero_de_ruta', 0);
                                $set('numero_seccion', 0);
                                $set('seccion_prioritaria', false);
                                $lacolonia = Colonia::find($get('colonia_id'));
                                if($lacolonia){
                                    $set('domicilio_codpost', $lacolonia->cod_post_colon);
                                }
                            }),

                            Toggle::make('colonia_catalogada')
                            ->label('Colonia en Catálogo?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->required()
                            ->live(),

                            TextInput::make('domicilio_colonia')
                            ->label('Colonia No Catalogada')
                            ->maxLength(60)
                            ->live()
                            ->disabled(function (Get $get) {
                                $opcion = $get('colonia_catalogada');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            TextInput::make('domicilio_completo')
                            ->label('Domicilio Calle y Número')
                            ->maxLength(80)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->required(),

                            TextInput::make('domicilio_codpost')
                            ->label('Código Postal')
                            ->live(),

                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos de Contacto')
                        ->description('Teléfonos y correo electrónico del contacto')
                        ->aside() 
                        ->schema([

                            Toggle::make('tiene_celular')
                            ->label('Tiene Celular Personal?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('telefono_movil')
                            ->label('Número del Móvil')
                            ->tel()
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_celular');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                            Toggle::make('tiene_watsapp')
                            ->label('Maneja Wattsapp?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->disabled(function (Get $get) {
                                $opcion = $get('tiene_celular');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            TextInput::make('telefono_familiar')
                            ->label('Núm. Teléfono de Casa')
                            ->tel(),

                            TextInput::make('telefono_recados')
                            ->label('Teléfono para Recados')
                            ->tel(),

                            Toggle::make('tiene_correo')
                            ->label('Tiene Correo Electrónico?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('cuenta_de_correo')
                            ->label('Su Cuenta de Correo')
                            ->email()
                            ->maxLength(80)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_correo');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos Redes Sociales')
                        ->description('Información de cuentas en redes del Contacto')
                        ->schema([

                            Toggle::make('tiene_facebook')
                            ->label('Tiene Cuenta en Facebook?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('contacto_facebook')
                            ->label('Su Cuenta de Facebook')
                            ->maxLength(60)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_facebook');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                            Toggle::make('tiene_instagram')
                            ->label('Tiene Cuenta en Instagram?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('contacto_instagram')
                            ->label('Su Cuenta de Instagram')
                            ->maxLength(60)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_instagram');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                            Toggle::make('tiene_telegram')
                            ->label('Tiene Cuenta en Telegram?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('contacto_telegram')
                            ->label('Su Cuenta de Telegram')
                            ->maxLength(60)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_telegram');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                            Toggle::make('tiene_twitter')
                            ->label('Tiene Cuenta en Twitter?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('contacto_twitter')
                            ->label('Su Cuenta de Twitter')
                            ->maxLength(60)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_twitter');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                            Toggle::make('tiene_otra_red')
                            ->label('Tiene Cuenta en Otra Red?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('contacto_otra_red')
                            ->label('Su Cuenta de Otra Red')
                            ->maxLength(60)
                            ->required(function (Get $get) {
                                $opcion = $get('tiene_otra_red');
                                if (isset($opcion)){
                                    return $opcion;
                                }
                                return false;
                            }),

                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),
                        
                        Section::make('Información Electoral')
                        ->description('Datos extraíos de la credencial del INE del Contacto')
                        ->schema([

                            Toggle::make('con_domi_actual')
                            ->label('INE con Domicilo Actual?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger'),

                            TextInput::make('numero_cred_ine')
                            ->label('Número Credencial del INE')
                            ->maxLength(20),

                            TextInput::make('clave_elector')
                            ->label('Teclear Clave de Elector')
                            ->maxLength(20),
                            
                            TextInput::make('numero_ocr_ine')
                            ->label('Número OCR del Reverso')
                            ->maxLength(20),
                            
                            DatePicker::make('vigencia_cred_ine')
                            ->label('Vigencia de la Credencial')
                            ->format('Y/m/d')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->subYears(10))
                            ->maxDate(now()->addYears(20)),

                            TextInput::make('distrito_federal')
                            ->label('Número Distrito Federtal')
                            ->numeric(),

                            TextInput::make('distrito_estatal')
                            ->label('Número Distrito Local')
                            ->numeric(),

                            TextInput::make('numero_de_ruta')
                            ->label('Número de la Ruta')
                            ->numeric(),

                            TextInput::make('numero_seccion')
                            ->label('Número de la Sección')
                            ->numeric(),

                            Toggle::make('seccion_prioritaria')
                            ->label('Es Sección Prioritaria?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger'),

                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Credencial del INE')
                        ->description('Registro de Imágenes de la Credencial para Votar')
                        ->schema([

                            Toggle::make('tiene_fotos_ine')
                            ->label('Tiene Fotos de Credencial INE?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger'),
                            
                            FileUpload::make('fotos_del_ine')
                            ->label('Fotos de Credencial INE (frente y reverso)')
                            ->multiple()
                            ->disk('public_media')
                            ->directory('Fotos_INE')
                            ->storeFileNamesIn('nombres_reales')
                            ->downloadable()
                            ->openable()
                            ->image()
                            ->maxSize(400)
                            ->maxFiles(2),

                            Hidden::make('nombres_reales'),
                            
                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Datos de Participación')
                        ->description('Información sobre participaciones actuales del Contacto')
                        ->schema([

                            Toggle::make('es_militante')
                            ->label('Es Militante del Partido?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('numero_afiliacion')
                            ->label('Número de Afiliación')
                            ->maxLength(20)
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_militante');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            DatePicker::make('fecha_afiliacion')
                            ->label('Fecha de Afiliación')
                            ->format('Y/m/d')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->subYears(20))
                            ->maxDate(now())
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_militante');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            TextInput::make('numero_credencial')
                            ->label('Número de Credencial')
                            ->maxLength(20)
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_militante');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            Toggle::make('en_comite')
                            ->label('Es Parte de un Comité Base?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            Select::make('comite_base')
                            ->label('Su Asignación Actual?')
                            ->options([
                                'INTEGRANTE'   => 'INTEGRANTE',
                                'ENLACE NÚM.1' => 'ENLACE NÚM.1',
                                'ENLACE NÚM.2' => 'ENLACE NÚM.2',
                                'No Aplica'    => 'NO APLICA',
                            ])
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_comite');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            Select::make('comite_rol')
                            ->label('Que Rol Desempeña?')
                            ->options([
                                'COORDINADOR'   => 'COORDINADOR',
                                'ES ACTIVISTA'  => 'ES ACTIVISTA',
                                'DEFENSOR VOTO' => 'DEFENSOR VOTO',
                                'MOVILIZADOR'   => 'MOVILIZADOR',
                                'ES AUXILIAR'   => 'ES AUXILIAR',
                                'OTROS'         => 'OTROS',
                                'No Aplica'     => 'NO APLICA',
                            ])
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_comite');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            Select::make('defensor_voto')
                            ->label('En la Defensa del Voto?')
                            ->options([
                                'ES COORDINADOR'      => 'ES COORDINADOR',
                                'REPRESENTANTE LEGAL' => 'REPRESENTANTE LEGAL',
                                'REP.CASILLA PROPIET' => 'REP.CASILLA PROPIET',
                                'REP:CASILLA SUPLENT' => 'REP:CASILLA SUPLENT',
                                'ES ASISTENTE'        => 'ES ASISTENTE',
                                'No Aplica'           => 'NO APLICA',
                            ])
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_comite');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            }),

                            Toggle::make('en_partido')
                            ->label('Es Integrante del Partido?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('partido_area')
                            ->label('En que Area del Partido?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_partido');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            TextInput::make('partido_puesto')
                            ->label('En que Puesto Actual?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_partido');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            TextInput::make('partido_lugar')
                            ->label('En que Lugar se Ubica?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('en_partido');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            Toggle::make('es_funcionario')
                            ->label('Es Funcionario Actualmente?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),

                            TextInput::make('puesto_cargo')
                            ->label('Que Puesto o Cargo Tiene?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_funcionario');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            TextInput::make('dependencia')
                            ->label('En que Dependencia?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_funcionario');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                            TextInput::make('ubicacion')
                            ->label('Dónde se Ubica su Oficina?')
                            ->maxLength(30)
                            ->disabled(function (Get $get) {
                                $opcion = $get('es_funcionario');
                                if (isset($opcion)){
                                    return !$opcion;
                                }
                                return true;
                            })
                            ->dehydrateStateUsing(function (string | NULL $state) {
                                if (isset($state)){
                                    return strtoupper($state);
                                }
                                return 'n/a';
                            }),

                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Areas de Interés')
                        ->description('Intereses personales manifestados por el Contacto')
                        ->schema([

                            Toggle::make('interesa_afiliacion')
                            ->label('Le Interesa Afiliarse')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_defensavoto')
                            ->label('Le Interesa Defender el Voto')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_armarcomite')
                            ->label('Le Interesa Armar Comité de Base')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_unirsecomite')
                            ->label('Le Interesa Unirse a un Comité')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_recibwatsaps')
                            ->label('Acepta Recibir Avisos por Watsapp')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_recibllamadas')
                            ->label('Acepta Recibir Llamadas Telefónicas')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_recibcorreos')
                            ->label('Acepta Recibir Correos Electrónicos')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_recibvisitas')
                            ->label('Acepta Recibir Visitas en Domicilio')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_capacitacion')
                            ->label('Le Interesa Recibir Capacitación')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_asistireventos')
                            ->label('Le Interesa Asistir a Eventos')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_asistirviajees')
                            ->label('Le Interesa Asistir a Viajes')
                            ->onColor('success')
                            ->offColor('danger'),

                            Toggle::make('interesa_darasesorias')
                            ->label('Le Interesa Ofrecer Asesorías')
                            ->onColor('success')
                            ->offColor('danger'),

                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Textos Adicionales')
                        ->description('Ideas y Opiniones del Contacto y Nuestras Observaciones')
                        ->schema([
                            
                            Textarea::make('sus_aportaciones')
                            ->label('Aportaciones del Contacto')
                            ->autosize()
                            ->maxLength(1024),

                            Textarea::make('mis_comentarios')
                            ->label('Observaciones y Comentarios')
                            ->autosize()
                            ->maxLength(1024),

                        ])
                        ->collapsible() 
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                    ])
                    ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('categoria.nombre')
                    ->label('Clasificación')
                    ->sortable(),
                TextColumn::make('owner_id')
                    ->label('Owns')
                    ->sortable(),
                TextColumn::make('nombre_completo')
                    ->label('Nombre del Contacto')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('fotos_del_ine')
                    ->label('Fotos INE')
                    ->square(),
                TextColumn::make('created_at')
                    ->label('Registrado')
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
            'index' => Pages\ListContactos::route('/'),
            'create' => Pages\CreateContacto::route('/create'),
            'edit' => Pages\EditContacto::route('/{record}/edit'),
        ];
    }    
}
