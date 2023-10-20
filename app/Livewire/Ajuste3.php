<?php

namespace App\Livewire;

use Exception;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class Ajuste3 extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $empezando;
    public $verificado;
    public $marca;

    public function mount()
    {
        $this->empezando = true;
        $this->verificado = false;
        $this->marca = false;
    }

    public function table(Table $table): Table
    {
        return $table

            ->query(User::query()->where('id', '=', Auth::user()->id))

            ->paginated(false)

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                Split::make([

                    TextColumn::make('id')
                        ->size(TextColumnSize::Large)
                        ->badge()
                        ->color('warning')
                        ->grow(false),

                    TextColumn::make('name')
                        ->size(TextColumnSize::Large)
                        ->color('warning')
                        ->grow(false),

                ])->from('md'),
            ])

            ->filters([
                //
            ])

            ->actions([

                Action::make('Verificación de su Identidad')
                ->visible(function (Component $livewire) {
                    return $livewire->empezando;
                })
                ->icon('tabler-user-search')
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('tabler-user-search')
                ->modalDescription('Haga el favor de teclear su password actual')
                ->modalCancelActionLabel('Siempre No... Cancela')
                ->modalSubmitActionLabel('Adelante... Verificar')
                ->form([
                    TextInput::make('contrasena')
                    ->label('Password')
                    ->autofocus()
                    ->minLength(8)
                    ->required(),
                ])
                ->action(function (User $record, array $data) {

                    $tecleada = $data['contrasena'];

                    if (Hash::check($tecleada, $record->password)) {

                        $this->makeNotification(
                            'Password es OK',
                            'Puede continuar con el Cambio de Password ',
                            'success',
                        )->send();
                        $this->verificado = true;
                        $this->empezando = false;

                    } else {

                        $this->makeNotification(
                            'NO COINCIDE !!!',
                            'LA PASSWORD ACTUAL ES INCORRECTA...',
                            'danger',
                        )->send();
                        $this->verificado = false;
                        $this->empezando = true;

                    }
                    
                }),

                Action::make('Hacer Cambio de Password')
                ->visible(function (Component $livewire) {
                    return $livewire->verificado;
                })
                ->requiresConfirmation()
                ->icon('tabler-key')
                ->color('fiucha')
                ->modalIcon('tabler-key')
                ->modalDescription('Al hacer el Cambio de Contraseña va a ser necesario que Ud. se vuelva a conectar al Sistema...')
                ->modalCancelActionLabel('CANCELAR')
                ->modalSubmitActionLabel('APLICAR CAMBIO')
                ->form([
                    TextInput::make('lanueva')
                    ->label('Password')
                    ->autofocus()
                    ->minLength(8)
                    ->required(),
                ])
                ->action(function (User $record, array $data) {

                    $ingresada = $data['lanueva'];
                    $encriptad = Hash::make($ingresada);
                    $userid = $record->id;

                    try {

                        User::Where('id', $userid)->update((['password' => $encriptad]));

                        Ajuste3::makeNotification(
                            'Cambio Exitoso',
                            'Su password fué cambiada con éxito!',
                            'success',
                        )->send();
                        $this->verificado = false;

                    } catch (Exception $e) {

                        Ajuste3::makeNotification(
                            'ERROR AL APLICAR CAMBIO',
                            $e->getMessage(),
                            'danger',
                        )->send();
                        $this->verificado = false;

                    }

                }),

            ], position: ActionsPosition::BeforeColumns)

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),

            ]);

    }

    public function render(): View
    {
        return view('livewire.ajuste3');
    }

    private static function makeNotification(string $title, string $body, string $color): Notification
    {
        return Notification::make('Resultados')
            ->icon('tabler-info-circle-filled')
            ->color($color)
            ->title($title)
            ->body($body);
    }

}
