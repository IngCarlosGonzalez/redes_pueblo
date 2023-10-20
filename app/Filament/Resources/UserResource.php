<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use App\Notifications\UserNotification;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

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

                        Hidden::make('bandera')
                        ->dehydrated(false),

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
                            ->minLength(8)
                            ->password(fn (Get $get): bool => $get('bandera'))
                            ->suffixActions([
                                Action::make('verPassword')
                                    ->icon('heroicon-m-eye')
                                    ->action(fn (Set $set) => $set('bandera', false)),
                                Action::make('noverPassword')
                                    ->icon('heroicon-m-eye-slash')
                                    ->action(fn (Set $set) => $set('bandera', true)),
                            ]),
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
                            ->label('Rol Principal')
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
            ->paginated([100, 'all'])
            ->defaultPaginationPageOption(100)
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre de Usuario')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Cuenta de Correo')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verificación')
                    ->dateTime()
                    ->wrap()
                    ->since(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo?')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('level_id')
                    ->label('Nivel')
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
                    ->wrap()
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Mensaje')
                ->requiresConfirmation()
                ->icon('tabler-mail-forward')
                ->color('naranja')
                ->modalIcon('tabler-mail-forward')
                ->modalDescription('Enviar mensaje por Correo Electrónico')
                ->modalCancelActionLabel('Siempre No... Cancela')
                ->modalSubmitActionLabel('Adelante... Enviar Correo')
                ->form([
                    Forms\Components\TextInput::make('subject')
                    ->label('Asunto:')
                    ->required(),
                    Forms\Components\Textarea::make('message')
                    ->label('Contenido:')
                    ->required(),
                ])
                ->action(function (User $user, array $data) {
                    //dd($data);
                    try {
                        $user->notify(new UserNotification($data['subject'], $data['message']));
                        UserResource::makeNotification(
                            'Envío Exitoso',
                            'El mensaje por correo se envió con éxito!',
                            'success',
                        )->send();
                    } catch (Exception $e) {
                        UserResource::makeNotification(
                            'Error al Enviar',
                            $e->getMessage(),
                            'danger',
                        )->send();
                    }
                }),
                /* Tables\Actions\Action::make('Recordatorio')
                ->visible(fn (User $user) => ! $user->hasVerifiedEmail())
                ->requiresConfirmation()
                ->icon('tabler-mail-forward')
                ->color('danger')
                ->modalIcon('tabler-mail-forward')
                ->modalDescription('Enviar recordatorio de verificación de su cuenta de correo')
                ->modalCancelActionLabel('Cancelar')
                ->modalSubmitActionLabel('Enviar Correo')
                ->action(function (User $user) {
                    //dd($user);
                    try {
                        $user->sendEmailVerificationNotification();
                        UserResource::makeNotification(
                            'Envío Exitoso',
                            'El mensaje por correo se envió con éxito!',
                            'success',
                        )->send();
                    } catch (Exception $e) {
                        UserResource::makeNotification(
                            'Error al Enviar',
                            $e->getMessage(),
                            'danger',
                        )->send();
                    }
                }), */
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

    private static function makeNotification(string $title, string $body, string $color = 'success'): Notification
    {
        return Notification::make('Resultados del Envío')
            ->color($color)
            ->title($title)
            ->body($body);
    }

}
