<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PayrollPeriod;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class HRSummaryWidget extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected static ?int $sort = 21;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $companyId = session('selected_company_id');
        if (!$companyId || $companyId === 'all') {
            return [];
        }

        $year = $this->filters['year'] ?? date('Y');
        
        $currentYearStart = Carbon::create($year, 1, 1)->startOfYear();
        $currentYearEnd = $currentYearStart->copy()->endOfYear();
        
        $lastYearStart = $currentYearStart->copy()->subYear();
        $lastYearEnd = $lastYearStart->copy()->endOfYear();

        $employeeCount = Employee::where('company_id', $companyId)->where('is_active', true)->count();

        // Attendance stats for the year
        // We count total unique employee days present vs working days (approx)
        $actualAttendances = Attendance::where('company_id', $companyId)
            ->whereBetween('date', [$currentYearStart, $currentYearEnd])
            ->count();
        
        // Approximate working days (roughly 260 per year)
        $approxWorkingDays = 260; 
        $totalPossibleAttendances = $employeeCount * $approxWorkingDays;
        
        $attendanceRate = $totalPossibleAttendances > 0 
            ? round(($actualAttendances / $totalPossibleAttendances) * 100, 1) 
            : 0;

        // Payroll Current Year
        $payrollTotal = PayrollPeriod::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('year', $year)
            ->sum('total_net_salary');

        // Payroll Last Year
        $lastPayrollTotal = PayrollPeriod::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('year', (int)$year - 1)
            ->sum('total_net_salary');

        $payGrowth = $lastPayrollTotal > 0 ? (($payrollTotal - $lastPayrollTotal) / $lastPayrollTotal) * 100 : 0;

        return [
            Stat::make('Active Staff', $employeeCount)
                ->description('Total personnel')
                ->color('info'),
            
            Stat::make('Yearly Avg Attendance', $attendanceRate . '%')
                ->description('Staff presence throughout ' . $year)
                ->color($attendanceRate > 90 ? 'success' : ($attendanceRate > 75 ? 'warning' : 'danger')),

            Stat::make('Total Yearly Payroll', 'Rp ' . number_format($payrollTotal, 0, ',', '.'))
                ->description(abs(round($payGrowth, 1)) . '% ' . ($payGrowth >= 0 ? 'up' : 'down') . ' YoY')
                ->descriptionIcon($payGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($payGrowth >= 0 ? 'primary' : 'success'),
        ];
    }
}
