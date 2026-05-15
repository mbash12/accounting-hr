<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AccountingSectionHeader extends Widget
{
    protected string $view = 'filament.widgets.dashboard-section-header';
    public string $title = 'Accounting Summary';
    public string $description = 'Financial overview, revenue trends, and operational costs.';
    public string $icon = 'heroicon-o-banknotes';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 10;
}
