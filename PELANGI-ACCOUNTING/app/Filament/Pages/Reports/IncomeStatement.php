<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class IncomeStatement extends Page implements HasForms
{
    use HasPageShield, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.income-statement';

    protected static string|UnitEnum|null $navigationGroup = 'Financial Reports';

    protected static ?string $navigationLabel = 'Income Statement';

    protected static ?string $title = 'Income Statement';

    public function getTitle(): string
    {
        return 'Income Statement';
    }

    public function getHeading(): string
    {
        return 'Income Statement';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('From Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->validate())
                    ->default(now()->startOfMonth()),

                DatePicker::make('end_date')
                    ->label('To Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->validate())
                    ->default(now())
                    ->suffixAction(function () {
                        return \Filament\Actions\Action::make('filter_date')
                            ->icon('heroicon-m-funnel')
                            ->action('filterReport')
                            ->color('primary');
                    }),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function getHeaderActions(): array
    {
        return [];
    }

    public function filterReport()
    {
        $this->validate();
        // Force the page to re-render with the new date
    }

    public function downloadPdf()
    {
        $data = $this->getRawData();

        if (isset($data['error'])) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.income-statement-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Income_Statement_'.now()->format('Ymd').'.pdf');
    }

    protected function getViewData(): array
    {
        return $this->getRawData();
    }

    protected function getRawData(): array
    {
        $startDate = filled($this->data['start_date'] ?? null)
            ? $this->data['start_date']
            : now()->startOfMonth()->format('Y-m-d');
        $endDate = filled($this->data['end_date'] ?? null)
            ? $this->data['end_date']
            : now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (! $companyId || $companyId === 'all') {
            return [
                'revenues' => collect(),
                'expenses' => collect(),
                'netIncome' => 0,
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Please select a specific company from the global selector to view the report.',
            ];
        }

        $accounts = Account::withTrashed()->where('company_id', $companyId)
            ->whereIn('account_type', array_merge(Account::REVENUE_TYPES, Account::EXPENSE_TYPES, [Account::OTHER_INCOME_EXPENSE]))
            ->orderBy('code')
            ->get();

        // transactions from start_date to end_date
        $movements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate, $companyId) {
                $q->where('company_id', $companyId);
                $q->whereBetween('date', [$startDate, $endDate]);
                $q->where('is_posted', true);
                // Closing entries zero P&L; exclude so year-end Income Statement still shows operating results.
                $q->excludePeriodClosing();
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // Calculate balances
        foreach ($accounts as $account) {
            $movement = $movements->get($account->id);
            $debit = $movement ? $movement->total_debit : 0;
            $credit = $movement ? $movement->total_credit : 0;

            $account->calculated_balance = $account->balanceFromMovements($debit, $credit);
        }

        // Build Tree
        $accountTree = $this->buildTree($accounts);

        // Calculate hierarchical balances
        $this->aggregateBalances($accountTree);

        // Filter out zero nodes
        $accountTree = $this->filterZeroBalanceAccounts($accountTree);

        // Split by accounting type, not by a customizable account-code prefix.
        $operatingRevenues = $this->cloneAndFilter($accountTree, fn ($a) => $a->account_type === 'revenue');
        $costOfGoodsSold = $this->cloneAndFilter($accountTree, fn ($a) => $a->account_type === 'cost_of_goods_sold');
        $operatingExpenses = $this->cloneAndFilter($accountTree, fn ($a) => $a->account_type === 'expense');
        $otherRevenues = $this->cloneAndFilter($accountTree, fn ($a) => $a->account_type === 'other_income');
        $otherExpenses = $this->cloneAndFilter($accountTree, fn ($a) => $a->account_type === 'other_expense');

        foreach ([$operatingRevenues, $costOfGoodsSold, $operatingExpenses, $otherRevenues, $otherExpenses] as $section) {
            $this->aggregateBalances($section);
        }

        $totalOperatingRevenue = $operatingRevenues->sum('calculated_balance');
        $totalCogs = $costOfGoodsSold->sum('calculated_balance');
        $grossProfit = $totalOperatingRevenue - $totalCogs;

        $totalOperatingExpense = $operatingExpenses->sum('calculated_balance');
        $operatingProfit = $grossProfit - $totalOperatingExpense;

        $totalOtherRevenue = $otherRevenues->sum('calculated_balance');
        $totalOtherExpense = $otherExpenses->sum('calculated_balance');

        $netIncome = $operatingProfit + $totalOtherRevenue - $totalOtherExpense;

        return [
            'operatingRevenues' => $operatingRevenues,
            'costOfGoodsSold' => $costOfGoodsSold,
            'operatingExpenses' => $operatingExpenses,
            'otherRevenues' => $otherRevenues,
            'otherExpenses' => $otherExpenses,
            'totalOperatingRevenue' => $totalOperatingRevenue,
            'totalCogs' => $totalCogs,
            'grossProfit' => $grossProfit,
            'totalOperatingExpense' => $totalOperatingExpense,
            'operatingProfit' => $operatingProfit,
            'totalOtherRevenue' => $totalOtherRevenue,
            'totalOtherExpense' => $totalOtherExpense,
            'netIncome' => $netIncome,
            'company' => Company::find($companyId),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    private function buildTree(Collection $accounts)
    {
        $grouped = $accounts->groupBy('parent_id');
        $accounts->each(function ($item) use ($grouped) {
            $item->children = $grouped->get($item->id, collect());
        });

        return $accounts->whereNull('parent_id');
    }

    private function cloneAndFilter($nodes, callable $leafCondition)
    {
        $filtered = collect();

        foreach ($nodes as $node) {
            $clone = clone $node;
            $clone->children = $this->cloneAndFilter($node->children ?? collect(), $leafCondition);

            if ($clone->children->isNotEmpty() || (! $clone->is_header && $leafCondition($clone))) {
                $filtered->push($clone);
            }
        }

        return $filtered;
    }

    private function aggregateBalances($nodes)
    {
        $total = 0;
        foreach ($nodes as $node) {
            if ($node->children->isNotEmpty()) {
                $childTotal = $this->aggregateBalances($node->children);
                // Roll child totals into parents that have no own movement (headers and intermediate folders).
                if ($node->is_header || abs((float) $node->calculated_balance) < 0.01) {
                    $node->calculated_balance = $childTotal;
                }
            }
            $total += $node->calculated_balance;
        }

        return $total;
    }

    private function filterZeroBalanceAccounts($nodes)
    {
        $filteredNodes = collect();

        foreach ($nodes as $node) {
            // Process children first
            if ($node->children->isNotEmpty()) {
                $node->children = $this->filterZeroBalanceAccounts($node->children);
            }

            // Include the node if:
            // 1. It has remaining children (header or intermediate parent)
            // 2. It's a leaf with a non-zero balance
            if ($node->children->isNotEmpty() || (! $node->is_header && $node->calculated_balance != 0)) {
                $filteredNodes->push($node);
            }
        }

        return $filteredNodes;
    }
}
