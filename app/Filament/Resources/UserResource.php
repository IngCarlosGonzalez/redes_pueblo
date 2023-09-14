<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Usuarios';

    protected static ?string $navigationLabel = 'Admin Usuarios';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'CONTROL';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos del Usuario')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de Usuario')
                            ->autofocus()
                            ->required()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                            ])
                            ->autocomplete(false)
                            ->default('')
                            ->placeholder('aqui el nombre')
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Cuenta de Correo')
                            ->email()
                            ->required()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                            ])
                            ->autocomplete(false)
                            ->default('')
                            ->placeholder('aqui la cuenta')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                            ])
                            ->autocomplete(false)
                            ->default('')
                            ->placeholder('aqui la password')
                            ->minLength(8),
                        Toggle::make('is_active')
                            ->label('Activo?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),
                        Toggle::make('is_admin')
                            ->label('Es Admin?')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-user')
                            ->required(),
                        Select::make('level_id')
                            ->label('Acceso')
                            ->options([
                                '1' => 'TODO',
                                '2' => 'DIVISION',
                                '3' => 'GRUPO',
                                '4' => 'CELULA',
                                '5' => 'nada',
                            ])
                            ->required(), 
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->required(),
                        ])
                    ->columns([
                            'sm' => 1,
                            'md' => 3,
                        ]
                    )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre de Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Cuenta de Correo')
                    ->icon('heroicon-m-envelope')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo?')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Es Admin?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('level_id')
                    ->label('Acceso a')
                    ->size(TextColumnSize::Large)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'primary',
                        '2' => 'warning',
                        '3' => 'success',
                        '4' => 'gray',
                        '5' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
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
                    //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
