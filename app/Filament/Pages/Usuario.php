<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Usuario extends Page 
{
    protected static ?string $title = 'Cambiar Password';
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Cambiar Password';
    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = 'Cambio de Password';
    protected ?string $subheading = 'Para el Usuario Actual';
    
    protected static string $view = 'filament.pages.usuario';

}
