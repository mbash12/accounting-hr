<?php

namespace App\Filament\Widgets;

use App\Models\JournalEntryItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class ExpenseBreakdownChart extends ChartWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected ?string $heading = 'Operating Expense Breakdown';
    protected static ?int $sort = 13;
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

        $expenses = JournalEntryItem::join('accounts', 'journal_entry_items.account_id', '=', 'accounts.id')
            ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.company_id', $companyId)
            ->where('journal_entries.is_posted', true)
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->where('accounts.code', 'like', '6%')
                    ->orWhere('accounts.code', 'like', '7%');
            })
            ->select('accounts.name', DB::raw('SUM(journal_entry_items.debit - journal_entry_items.credit) as total'))
            ->groupBy('accounts.name')
            ->having(DB::raw('SUM(journal_entry_items.debit - journal_entry_items.credit)'), '>', 0)
            ->orderByDesc(DB::raw('SUM(journal_entry_items.debit - journal_entry_items.credit)'))
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Expenses',
                    'data' => $expenses->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ],
                ],
            ],
            'labels' => $expenses->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
