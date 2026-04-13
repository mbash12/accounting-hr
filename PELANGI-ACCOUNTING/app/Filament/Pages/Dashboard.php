<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function getNavigationLabel(): string
    {
        return __('filament-panels::pages/dashboard.title');
    }
    
    public static function getNavigationIcon(): ?string
    {
        return asset('icons/dashboard.svg');
    }
    
    public static function getNavigationSort(): int
    {
        return -1;
    }
}