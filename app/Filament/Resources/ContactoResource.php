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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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

                        Section::make('Datos Básicos')
                        ->description('Identificación y Clasificación del Contacto')
                        ->aside() 
                        ->schema([

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
                
                            TextInput::make('nombre_completo')
                            ->label('Nombre Completo')
                            ->autofocus()
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
                            ->label('Clave de la CURP')
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->maxLength(20),

                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos del Domicilio')
                        ->description('Ubicación del domicilio particular del Contacto')
                        ->aside() 
                        ->schema([
                            
                            Select::make('municipio_id')
                            ->options(Municipio::all()->pluck('nombre', 'id')->toArray())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('colonia_id', null)),

                            Select::make('colonia_id')
                            ->options(function (Get $get) {
                                $mpio = Municipio::find($get('municipio_id'));
                                if($mpio){
                                    return $mpio->colonias->pluck('nombre_colonia', 'id');
                                }
                            })
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('colonia_catalogada', true)),

                            Toggle::make('colonia_catalogada')
                            ->label('Colonia en Catálogo?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->live()
                            ->required()
                            ->afterStateUpdated(fn (Set $set) => $set('domicilio_colonia', 'n/a')),

                            TextInput::make('domicilio_colonia')
                            ->label('Colonia No Catalogada')
                            ->maxLength(60)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state)),

                            TextInput::make('domicilio_completo')
                            ->label('Domicilio Calle y Número')
                            ->maxLength(80)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->required(),

                            TextInput::make('domicilio_codpost')
                            ->label('Código Postal')
                            ->numeric()
                            ->mask('99999')
                            ->minLength(5)
                            ->required(),

                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos de Contacto')
                        ->description('Teléfonos y correo electrónico del contacto')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos Redes Sociales')
                        ->description('Información de cuentas en redes del Contacto')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Credencial del INE')
                        ->description('Registro de Imágenes de la Credencial para Votar')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Información Electoral')
                        ->description('Datos extraíos de la credencial del INE del Contacto')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Datos de Participación')
                        ->description('Información sobre participaciones actuales del Contacto')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Areas de Interés')
                        ->description('Intereses personales manifestados por el Contacto')
                        ->aside() 
                        ->schema([
                            //
                        ])
                        ->collapsed() 
                        ->compact()
                        ->columns(1),

                        Section::make('Textos Registrados')
                        ->description('Ideas y Opiniones del Contacto y Nuestras Observaciones')
                        ->aside() 
                        ->schema([
                            //
                        ])
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
                TextColumn::make('nombre_completo')
                    ->label('Nombre del Contacto')
                    ->searchable()
                    ->sortable(),
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
