<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Support\Facades\Log;

class CambiaPassword extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public User $usuario;

    public $palabra;
    
    public function cambiarAction(): Action
    {
        
        return Action::make('procesar')
            ->requiresConfirmation()
            ->size('xl')
            ->color('primary')
            ->action(function () {
                Log::info('Nueva password <' . $this->palabra . '> recibida...');
                return true;
            });
            
    }

    public function render()
    {
        $user = auth()->user(); 
        return view('livewire.cambia-password', compact('user'));
    }
}
