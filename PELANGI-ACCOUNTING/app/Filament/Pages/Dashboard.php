<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Pages\Dashboard\Actions\FilterAction;

use Filament\Forms\Components\Select;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\PulseSummaryHeader::class,
            \App\Filament\Widgets\TopPulseStatsWidget::class,

            \App\Filament\Widgets\AccountingSectionHeader::class,
            \App\Filament\Widgets\AccountingSummaryWidget::class,
            \App\Filament\Widgets\ProfitLossChart::class,
            \App\Filament\Widgets\ExpenseBreakdownChart::class,

            \App\Filament\Widgets\HRSectionHeader::class,
            \App\Filament\Widgets\HRSummaryWidget::class,
            \App\Filament\Widgets\MonthlyPayrollChart::class,

            \App\Filament\Widgets\HROperationsHeader::class,
            \App\Filament\Widgets\HROperationsSummaryWidget::class,
            \App\Filament\Widgets\PermitTypeChart::class,
            \App\Filament\Widgets\OvertimeTrendChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->form([
                    Select::make('year')
                        ->label('Pilih Tahun Analisis')
                        ->options(function() {
                            $years = range(date('Y'), date('Y') - 5);
                            return array_combine($years, $years);
                        })
                        ->default(date('Y')),
                ]),
        ];
    }


    public function getColumns(): int | array
    {
        return 2;
    }

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