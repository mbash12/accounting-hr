<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class HROperationsHeader extends Widget
{
    protected string $view = 'filament.widgets.dashboard-section-header';
    public string $title = 'HR Operations Hub';
    public string $description = 'Operational insights including attendance exceptions, permits, and overtime analysis.';
    public string $icon = 'heroicon-o-clipboard-document-check';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 30;
}
