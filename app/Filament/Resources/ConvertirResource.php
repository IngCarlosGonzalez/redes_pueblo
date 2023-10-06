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
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ConvertirResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ConvertirResource\RelationManagers;
use App\Filament\Resources\ConvertirResource\Pages\EditConvertir;
use App\Filament\Resources\ConvertirResource\Pages\ListConvertirs;
use App\Filament\Resources\ConvertirResource\Pages\CreateConvertir;

class ConvertirResource extends Resource
{
    protected static ?string $model = Contacto::class;

    protected static ?string $modelLabel = 'Prospecto';

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

                                TextInput::make('clave_tipo')
                                ->label('Tipo de Contacto')
                                ->prefixIcon('heroicon-m-user-circle')
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
                                                $set('requerimiento', 'Registro...' . $ident);
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 3) {
                                                    return true;
                                                }
                                                if ($nivel == 5) {
                                                    $nuevo = 4;
                                                } elseif ($nivel == 4) {
                                                    $nuevo = 3;
                                                } else {
                                                    $nuevo = 2;
                                                }
                                                //$catego = 16;
                                                if ($get('tiene_celular') && $get('tiene_correo')) {
                                                    //$set('categoría_id', $catego);
                                                    //$affected = DB::update('update contactos set categoria_id = 16 where id = :id', ['id' => $ident]);
                                                    //$set('requerimiento', 'Updated...' . $affected);
                                                    $set('nivel_en_red', $nuevo);
                                                    $set('clave_tipo', 'Organizador');
                                                    $set('requerimiento', 'Click en guardar...');
                                                } else {
                                                    $set('requerimiento', 'LE FALTAN DATOS');
                                                }
                                            }),

                                        Action::make('Disminir el Nivel')
                                            ->icon('heroicon-m-chevron-double-down')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->action(function (Get $get, Set $set) {
                                                $nivel = $get('nivel_en_red');
                                                if ($nivel < 2) {
                                                    return true;
                                                }
                                                if ($nivel > 4) {
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
                                                    //$catego = 15;
                                                    if ($nuevo == 5) {
                                                        //$set('categoría_id', $catego);
                                                        $set('clave_tipo', 'Integrante');
                                                    }
                                                    $set('requerimiento', 'Click en guardar...');
                                                } else {
                                                    $set('requerimiento', 'LE FALTAN DATOS');
                                                }
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

                /* TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime()
                    ->sortable()
                    ->since(), */
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
