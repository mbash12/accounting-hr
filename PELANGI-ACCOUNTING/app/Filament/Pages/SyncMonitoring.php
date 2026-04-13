<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BackedEnum;
use UnitEnum;

class SyncMonitoring extends Page
{
    use HasPageShield;

    protected static ?string $navigationLabel = 'Monitoring Sinkronisasi';

    protected static string | UnitEnum | null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'Monitoring Sinkronisasi Invoice';

    protected ?string $heading = 'Monitoring Sinkronisasi Invoice ke Inventory';

    protected static string | BackedEnum | null $navigationIcon = null;

    protected string $view = 'filament.pages.sync-monitoring';

    public static function getNavigationLabel(): string
    {
        return __('Monitoring Sinkronisasi');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public function getTitle(): string
    {
        return __('Monitoring Sinkronisasi Invoice ke Inventory');
    }

    public function getHeading(): string
    {
        return __('Monitoring Sinkronisasi Invoice ke Inventory');
    }

    public function getSubheading(): ?string
    {
        return __('Pantau dan kelola status sinkronisasi faktur penjualan ke sistem Inventory');
    }
}
