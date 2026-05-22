<?php

namespace App\Filament\Widgets;

use App\Models\JournalEntryItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ProfitLossChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Profit & Loss (Last 12 Months)';
    protected static ?int $sort = 12;
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
            'pgsql' => "to_char(journal_entries.date, 'YYYY-MM')",
            'sqlite' => "strftime('%Y-%m', journal_entries.date)",
            'mysql', 'mariadb' => "DATE_FORMAT(journal_entries.date, '%Y-%m')",
            default => "strftime('%Y-%m', journal_entries.date)",
        };

        // Get Revenue Data
        $revenueData = JournalEntryItem::join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounts', 'journal_entry_items.account_id', '=', 'accounts.id')
            ->where('journal_entries.company_id', $companyId)
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->where('journal_entries.is_posted', true)
            ->where(function($q) {
                $q->where('accounts.code', 'like', '4%')
                  ->orWhere('accounts.code', 'like', '8%');
            })
            ->select(
                DB::raw("$dateSelect as month"),
                DB::raw('SUM(credit - debit) as total')
            )
            ->groupBy(DB::raw("$dateSelect"))
            ->get()
            ->keyBy('month');

        // Get Expense Data
        $expenseData = JournalEntryItem::join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounts', 'journal_entry_items.account_id', '=', 'accounts.id')
            ->where('journal_entries.company_id', $companyId)
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->where('journal_entries.is_posted', true)
            ->where(function($q) {
                $q->where('accounts.code', 'like', '5%')
                  ->orWhere('accounts.code', 'like', '6%')
                  ->orWhere('accounts.code', 'like', '7%')
                  ->orWhere('accounts.code', 'like', '9%');
            })
            ->select(
                DB::raw("$dateSelect as month"),
                DB::raw('SUM(debit - credit) as total')
            )
            ->groupBy(DB::raw("$dateSelect"))
            ->get()
            ->keyBy('month');

        $revenues = [];
        $expenses = [];
        $labels = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthObj = Carbon::create($year, $m, 1);
            $monthKey = $monthObj->format('Y-m');
            
            $labels[] = $monthObj->format('M');
            $revenues[] = (float) ($revenueData->get($monthKey)?->total ?? 0);
            $expenses[] = (float) ($expenseData->get($monthKey)?->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Expenses',
                    'data' => $expenses,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
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
