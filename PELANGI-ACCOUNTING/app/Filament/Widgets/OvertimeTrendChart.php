<?php

namespace App\Filament\Widgets;

use App\Models\OvertimeLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class OvertimeTrendChart extends ChartWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected ?string $heading = 'Monthly Overtime Trend';
    protected static ?int $sort = 33;
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

        $driver = DB::connection()->getDriverName();
        $dateSelect = match($driver) {
            'pgsql' => "to_char(date, 'YYYY-MM')",
            'sqlite' => "strftime('%Y-%m', date)",
            'mysql', 'mariadb' => "DATE_FORMAT(date, '%Y-%m')",
            default => "strftime('%Y-%m', date)",
        };

        $overtimeData = OvertimeLog::where('company_id', $companyId)
            ->where('status', 'approved')
            ->whereBetween('date', [$startDate, $endDate])
            ->select(
                DB::raw("$dateSelect as month"),
                DB::raw('SUM(hours) as total_hours')
            )
            ->groupBy(DB::raw("$dateSelect"))
            ->get()
            ->keyBy('month');

        $data = [];
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthObj = Carbon::create($year, $m, 1);
            $monthKey = $monthObj->format('Y-m');
            
            $labels[] = $monthObj->format('M');
            $data[] = (float) ($overtimeData->get($monthKey)?->total_hours ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Overtime Hours',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderColor' => '#f59e0b',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
