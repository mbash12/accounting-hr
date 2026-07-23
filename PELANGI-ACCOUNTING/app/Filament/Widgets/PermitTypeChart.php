<?php

namespace App\Filament\Widgets;

use App\Models\Permit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PermitTypeChart extends ChartWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected ?string $heading = 'Permit & Leave Distribution';
    protected static ?int $sort = 32;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $companyId = session('selected_company_id');
        if (!$companyId || $companyId === 'all') {
            return ['datasets' => [], 'labels' => []];
        }

        $year = $this->filters['year'] ?? date('Y');
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $permits = Permit::where('company_id', $companyId)
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Permits',
                    'data' => $permits->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                    ],
                ],
            ],
            'labels' => $permits->pluck('type')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
