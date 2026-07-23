<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\JournalEntryItem;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class AccountingSummaryWidget extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected static ?int $sort = 11;
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
        
        $lastYearStart = $startDate->copy()->subYear();
        $lastYearEnd = $lastYearStart->copy()->endOfYear();

        // Revenue Current Year
        $revenue = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $startDate, $endDate) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$startDate, $endDate])
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

        // Expenses Current Year
        $expenses = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $startDate, $endDate) {
            $q->where('company_id', $companyId)
                ->whereBetween('date', [$startDate, $endDate])
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

        // Expenses Last Year
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

        // Cash & Bank (Current Balance as of year-end)
        $cashMovement = JournalEntryItem::whereHas('journalEntry', function ($q) use ($companyId, $endDate) {
            $q->where('company_id', $companyId)
                ->where('date', '<=', $endDate)
                ->where('is_posted', true);
        })
        ->whereHas('account', function ($q) {
            $q->where('is_cash_bank', true)
                ->where('is_header', false);
        })
        ->select(DB::raw('SUM(debit - credit) as total'))
        ->first()->total ?? 0;

        $openingBalance = Account::where('company_id', $companyId)
            ->where('is_cash_bank', true)
            ->where('is_header', false)
            ->sum('opening_balance');
        
        $totalCash = $cashMovement + $openingBalance;

        // Outstanding A/R as of year-end
        $totalAR = SalesInvoice::where('company_id', $companyId)
            ->where('is_locked', true)
            ->where('is_paid', false)
            ->where('date', '<=', $endDate)
            ->sum(DB::raw('CAST(outstanding_amount AS NUMERIC)'));

        // Outstanding A/P as of year-end
        $totalAP = PurchaseInvoice::where('company_id', $companyId)
            ->where('is_locked', true)
            ->where('is_paid', false)
            ->where('date', '<=', $endDate)
            ->sum(DB::raw('CAST(outstanding_amount AS NUMERIC)'));

        return [
            Stat::make('Yearly Revenue', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description(abs(round($revGrowth, 1)) . '% ' . ($revGrowth >= 0 ? 'up' : 'down') . ' YoY')
                ->descriptionIcon($revGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revGrowth >= 0 ? 'success' : 'danger'),
            Stat::make('Yearly Net Profit', 'Rp ' . number_format($netProfit, 0, ',', '.'))
                ->description(abs(round($profGrowth, 1)) . '% ' . ($profGrowth >= 0 ? 'up' : 'down') . ' YoY')
                ->descriptionIcon($profGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profGrowth >= 0 ? 'success' : 'danger'),
            Stat::make('Bank Balance', 'Rp ' . number_format($totalCash, 0, ',', '.'))
                ->description('Status as of Dec 31, ' . $year)
                ->color('primary'),
            Stat::make('Outstanding A/R', 'Rp ' . number_format($totalAR, 0, ',', '.'))
                ->description('Total collectible at year end')
                ->color('warning'),
            Stat::make('Outstanding A/P', 'Rp ' . number_format($totalAP, 0, ',', '.'))
                ->description('Total payable at year end')
                ->color('danger'),
        ];
    }
}
