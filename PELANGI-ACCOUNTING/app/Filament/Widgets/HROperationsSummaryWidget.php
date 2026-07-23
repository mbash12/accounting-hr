<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\OvertimeLog;
use App\Models\Permit;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class HROperationsSummaryWidget extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected static ?int $sort = 31;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $companyId = session('selected_company_id');
        if (!$companyId || $companyId === 'all') {
            return [];
        }

        $year = $this->filters['year'] ?? date('Y');
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        // Approved Permits count
        $permitCount = Permit::where('company_id', $companyId)
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->count();

        // Total Overtime Hours
        $totalOvertime = OvertimeLog::where('company_id', $companyId)
            ->where('status', 'approved')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('hours');

        // Total Late Minutes
        $totalLateMinutes = Attendance::where('company_id', $companyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('late_minutes');

        $employeeCount = Employee::where('company_id', $companyId)->where('is_active', true)->count();
        $avgLatePerStaff = $employeeCount > 0 ? round($totalLateMinutes / $employeeCount, 0) : 0;

        return [
            Stat::make('Approved Permits', $permitCount)
                ->description('Total leave & permits for the year')
                ->icon('heroicon-m-document-text')
                ->color('info'),
            
            Stat::make('Overtime Utilization', round($totalOvertime, 1) . ' hrs')
                ->description('Total approved overtime hours')
                ->icon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Late Frequency', number_format($totalLateMinutes, 0, ',', '.') . ' min')
                ->description('Total accumulated late minutes')
                ->icon('heroicon-m-hand-raised')
                ->color($totalLateMinutes > 1000 ? 'danger' : 'success'),

            Stat::make('Avg Late/Staff', $avgLatePerStaff . ' min')
                ->description('Average late minutes per employee')
                ->icon('heroicon-m-user')
                ->color($avgLatePerStaff > 60 ? 'danger' : 'success'),
        ];
    }
}
