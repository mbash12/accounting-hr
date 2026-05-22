<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\JournalEntryItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopPulseStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

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

        // Revenue Current Year
        $revenue = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $currentYearStart, $currentYearEnd) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$currentYearStart, $currentYearEnd])
                ->where('is_posted', true);
        })
        ->whereHas('account', function ($q) {
            $q->where('code', 'like', '4%')
                ->orWhere('code', 'like', '8%');
        })
        ->select(DB::raw('SUM(credit - debit) as total'))
        ->first()->total ?? 0;

        // Revenue Last Year
        $lastRevenue = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $lastYearStart, $lastYearEnd) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$lastYearStart, $lastYearEnd])
                ->where('is_posted', true);
        })
        ->whereHas('account', function ($q) {
            $q->where('code', 'like', '4%')
                ->orWhere('code', 'like', '8%');
        })
        ->select(DB::raw('SUM(credit - debit) as total'))
        ->first()->total ?? 0;

        // Profit Current Year
        $expenses = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $currentYearStart, $currentYearEnd) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$currentYearStart, $currentYearEnd])
                ->where('is_posted', true);
        })
        ->whereHas('account', function ($q) {
            $q->where(function($query) {
                $query->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '6%')
                    ->orWhere('code', 'like', '7%')
                    ->orWhere('code', 'like', '9%');
            });
        })
        ->select(DB::raw('SUM(debit - credit) as total'))
        ->first()->total ?? 0;

        // Profit Last Year
        $lastExpenses = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $lastYearStart, $lastYearEnd) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$lastYearStart, $lastYearEnd])
                ->where('is_posted', true);
        })
        ->whereHas('account', function ($q) {
            $q->where(function($query) {
                $query->where('code', 'like', '5%')
                    ->orWhere('code', 'like', '6%')
                    ->orWhere('code', 'like', '7%')
                    ->orWhere('code', 'like', '9%');
            });
        })
        ->select(DB::raw('SUM(debit - credit) as total'))
        ->first()->total ?? 0;

        $netProfit = $revenue - $expenses;
        $lastNetProfit = $lastRevenue - $lastExpenses;

        $revGrowth = $lastRevenue > 0 ? (($revenue - $lastRevenue) / $lastRevenue) * 100 : 0;
        $profGrowth = $lastNetProfit > 0 ? (($netProfit - $lastNetProfit) / $lastNetProfit) * 100 : 0;

        // People Pulse
        $employeeCount = Employee::where('company_id', $companyId)->where('is_active', true)->count();
        
        $totalPossibleAttendances = Attendance::where('company_id', $companyId)
            ->whereBetween('date', [$currentYearStart, $currentYearEnd])
            ->count();
        
        return [
            Stat::make('Yearly Revenue', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description(abs(round($revGrowth, 1)) . '% ' . ($revGrowth >= 0 ? 'up' : 'down') . ' YoY')
                ->descriptionIcon($revGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revGrowth >= 0 ? 'success' : 'danger'),
            Stat::make('Yearly Net Profit', 'Rp ' . number_format($netProfit, 0, ',', '.'))
                ->description(abs(round($profGrowth, 1)) . '% ' . ($profGrowth >= 0 ? 'up' : 'down') . ' YoY')
                ->descriptionIcon($profGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profGrowth >= 0 ? 'success' : 'danger'),
            Stat::make('Staff Count', $employeeCount)
                ->icon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Total Attendances', $totalPossibleAttendances)
                ->description('Total log presence this year')
                ->icon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}
