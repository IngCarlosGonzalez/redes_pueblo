<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contacto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
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
                                    'SinOrigen'   => 'Sin Origen',
                                    'EnOficinas'  => 'En Oficinas',
                                    'EnBrigadas'  => 'En Brigadas',
                                    'Referencias' => 'Referencias',
                                    'Importacion' => 'Importación',
                                    'Otros'       => 'Otros',
                                ])
                                ->required(),

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
                                    'Sin Datos'  => 'Sin Datos',
                                    'Femenino'   => 'Femenino',
                                    'Masculino'  => 'Masculino',
                                    'Diversidad' => 'Diversidad',
                                ])
                                ->required(),

                            DatePicker::make('fecha_nacimiento')
                                ->label('Fecha de Nacimiento')
                                ->format('d/m/Y')
                                ->minDate(now()->subYears(100))
                                ->maxDate(now())
                                ->required(),

                            TextInput::make('dato_de_curp')
                                ->label('Clave de la CURP')
                                ->maxLength(20)
                                ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                                ->required(),

                        ])
                        ->compact()
                        ->columns(1),

                        Section::make('Datos del Domicilio')
                        ->description('Ubicación del domicilio particular del Contacto')
                        ->aside() 
                        ->schema([
                            
                            TextInput::make('domicilio_calle')
                                ->label('Domicilio Calle')
                                ->maxLength(60)
                                ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                                ->required(),
                            
                            TextInput::make('domicilio_numext')
                                ->label('Número Exterior')
                                ->numeric()
                                ->maxLength(10)
                                ->required(),
                            
                            TextInput::make('domicilio_numint')
                                ->label('Número Interior')
                                ->maxLength(10),
                            
                            TextInput::make('domicilio_colonia')
                                ->label('Colonia')
                                ->maxLength(60)
                                ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                                ->required(),
                            
                            TextInput::make('domicilio_localidad')
                                ->label('Localidad')
                                ->maxLength(60)
                                ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                                ->required(),
                            
                            TextInput::make('domicilio_municipio')
                                ->label('Municipio')
                                ->maxLength(60)
                                ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                                ->required(),
                            
                            TextInput::make('domicilio_codpost')
                                ->label('Código Postal')
                                ->numeric()
                                ->mask('99999')
                                ->maxLength(5)
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
