<?php

namespace App\Filament\Widgets;

use App\Models\Permit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PermitTypeChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Distribusi Izin & Cuti';
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
                    'label' => 'Total Izin',
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
