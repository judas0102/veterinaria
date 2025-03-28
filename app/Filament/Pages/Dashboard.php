<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';

    // Evita que aparezca en el menú:
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.dashboard';

    public function getHeading(): string
    {
        $userName = Filament::auth()->user()->name ?? 'Usuario';
        return "Bienvenido, {$userName}";
    }
}
