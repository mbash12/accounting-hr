<?php

namespace App\Filament\Widgets;

use App\Models\PayrollPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class MonthlyPayrollChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Salary Expenditure Trend (12 Months)';
    protected static ?int $sort = 22;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $companyId = session('selected_company_id');
        if (!$companyId || $companyId === 'all') {
            return ['datasets' => [], 'labels' => []];
        }

        $year = $this->filters['year'] ?? date('Y');

        $payrollData = PayrollPeriod::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('year', $year)
            ->select(
                'month',
                DB::raw('SUM(total_gross_salary) as gross'),
                DB::raw('SUM(total_net_salary) as net')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        $grossData = [];
        $netData = [];
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthObj = Carbon::create($year, $m, 1);
            $labels[] = $monthObj->format("M");
            
            $grossData[] = (float) ($payrollData->get($m)->gross ?? 0);
            $netData[] = (float) ($payrollData->get($m)->net ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Gross Salary',
                    'data' => $grossData,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                ],
                [
                    'label' => 'Net Salary',
                    'data' => $netData,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
