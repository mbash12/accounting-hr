<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PulseSummaryHeader extends Widget
{
    use HasWidgetShield;
    protected string $view = 'filament.widgets.dashboard-section-header';
    public string $title = 'Business Pulse';
    public string $description = 'Real-time overview of your company health across finance and personnel.';
    public string $icon = 'heroicon-o-bolt';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = -1;
}
