<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class HRSectionHeader extends Widget
{
    protected string $view = 'filament.widgets.dashboard-section-header';
    public string $title = 'HR & Payroll Summary';
    public string $description = 'Personnel statistics, attendance rates, and payroll trends.';
    public string $icon = 'heroicon-o-users';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 20;
}
