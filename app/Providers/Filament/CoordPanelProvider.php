<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Pages\Usuario;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\SoloUsuarioCoordinador;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class CoordPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('coord')
            ->path('coord')
            ->login()
            ->sidebarFullyCollapsibleOnDesktop()
            ->emailVerification()
            ->font('Inter')
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Rose,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'naranja' => Color::Orange,
                'fiucha'  => Color::Fuchsia,
            ])
            ->discoverResources(in: app_path('Filament/Coord/Resources'), for: 'App\\Filament\\Coord\\Resources')
            ->discoverPages(in: app_path('Filament/Coord/Pages'), for: 'App\\Filament\\Coord\\Pages')
            ->pages([
                //
            ])
            ->discoverWidgets(in: app_path('Filament/Coord/Widgets'), for: 'App\\Filament\\Coord\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('CONTACTOS'),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Password')
                    ->url(fn (): string => Usuario::getUrl())
                    ->icon('heroicon-o-key'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                SoloUsuarioCoordinador::class,
            ]);
    }
}
