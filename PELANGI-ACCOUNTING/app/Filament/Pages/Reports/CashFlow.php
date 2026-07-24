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

class CashFlow extends Page implements HasForms
{
    use HasPageShield, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.cash-flow';

    protected static string|UnitEnum|null $navigationGroup = 'Financial Reports';

    protected static ?string $navigationLabel = 'Cash Flow';

    protected static ?string $title = 'Cash Flow Statement';

    public function getTitle(): string
    {
        return 'Cash Flow Statement';
    }

    public function getHeading(): string
    {
        return 'Cash Flow Statement';
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
                    ->default(now()),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function filterReport()
    {
        $this->validate();
    }

    public function downloadPdf()
    {
        $reportData = $this->getReportData();

        if (isset($reportData['error']) || ! $reportData['company']) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.cash-flow-pdf', $reportData);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Cash_Flow_'.now()->format('Ymd').'.pdf');
    }

    public function getReportData(): array
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
                'error' => 'Please select a specific company.',
                'company' => null,
            ];
        }

        $company = Company::find($companyId);
        $allAccounts = Account::withTrashed()->where('company_id', $companyId)->get();
        $hasPostedOpeningJournal = \App\Models\JournalEntry::query()
            ->where('company_id', $companyId)
            ->where('sub_module', 'opening_balance')
            ->where('is_posted', true)
            ->exists();

        $movements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate, $companyId) {
                $q->where('company_id', $companyId);
                $q->whereBetween('date', [$startDate, $endDate]);
                $q->where('is_posted', true);
                $q->where(function ($query) {
                    $query->whereNull('sub_module')
                        ->orWhere('sub_module', '!=', 'opening_balance');
                });
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        foreach ($allAccounts as $account) {
            $movement = $movements->get($account->id);
            $debit = $movement ? $movement->total_debit : 0;
            $credit = $movement ? $movement->total_credit : 0;
            // Cash flow effect logic: Credit - Debit
            $account->calculated_balance = $credit - $debit;
        }

        $baseTree = $this->buildBaseTree($allAccounts);

        // Indirect method: profit/loss plus non-cash adjustments and balance-sheet movements.
        $plCondition = fn ($a) => $a->isRevenueAccount() || $a->isExpenseAccount();
        $opAssetsCondition = fn ($a) => $a->account_type === 'current_asset' && ! $a->is_cash_bank;
        $opLiabilitiesCondition = fn ($a) => $a->account_type === 'current_liability';
        $invCondition = fn ($a) => $a->cash_flow === 'investing'
            && ! $a->isRevenueAccount() && ! $a->isExpenseAccount();
        $finCondition = fn ($a) => $a->cash_flow === 'financing'
            && ! $a->isRevenueAccount() && ! $a->isExpenseAccount();

        $nonCashCondition = fn ($a) => $a->isExpenseAccount() && $a->cash_flow === 'undefined';

        $plTree = $this->cloneAndFilter($baseTree, $plCondition);
        $this->aggregateBalances($plTree);
        $plTotal = $plTree->sum('calculated_balance');

        $nonCashTree = $this->cloneAndFilter($baseTree, $nonCashCondition);
        $this->aggregateBalances($nonCashTree);
        // Depreciation entries are debits (credit-debit < 0), so flip sign to add them back
        $nonCashTotal = -1 * $nonCashTree->sum('calculated_balance');

        $opAssetsTree = $this->cloneAndFilter($baseTree, $opAssetsCondition);
        $this->aggregateBalances($opAssetsTree);
        $opAssetsTotal = $opAssetsTree->sum('calculated_balance');

        $opLiabTree = $this->cloneAndFilter($baseTree, $opLiabilitiesCondition);
        $this->aggregateBalances($opLiabTree);
        $opLiabTotal = $opLiabTree->sum('calculated_balance');

        $invTree = $this->cloneAndFilter($baseTree, $invCondition);
        $this->aggregateBalances($invTree);
        $invTotal = $invTree->sum('calculated_balance');

        $finTree = $this->cloneAndFilter($baseTree, $finCondition);
        $this->aggregateBalances($finTree);
        $finTotal = $finTree->sum('calculated_balance');

        // Extract Cash
        $cashAccountIds = $allAccounts->where('is_cash_bank', true)->pluck('id')->toArray();

        $beginningCashMovements = JournalEntryItem::select(
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($q) use ($startDate, $companyId) {
                $q->where('company_id', $companyId);
                $q->where(function ($query) use ($startDate) {
                    $query->whereDate('date', '<', $startDate)
                        ->orWhere(function ($openingQuery) use ($startDate) {
                            $openingQuery->whereDate('date', $startDate)
                                ->where('sub_module', 'opening_balance');
                        });
                });
                $q->where('is_posted', true);
            })
            ->first();

        $legacyOpeningCash = $allAccounts->where('is_cash_bank', true)
            ->sum(fn (Account $account) => $account->reportOpeningBalance($hasPostedOpeningJournal));
        $beginningCash = $legacyOpeningCash + ($beginningCashMovements->total_debit ?? 0) - ($beginningCashMovements->total_credit ?? 0);

        $endingCashMovements = JournalEntryItem::select(
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($q) use ($endDate, $companyId) {
                $q->where('company_id', $companyId);
                $q->whereDate('date', '<=', $endDate);
                $q->where('is_posted', true);
            })
            ->first();

        $endingCash = $legacyOpeningCash + ($endingCashMovements->total_debit ?? 0) - ($endingCashMovements->total_credit ?? 0);

        $operatingTotal = $plTotal + $nonCashTotal + $opAssetsTotal + $opLiabTotal;
        $netCashFlow = $operatingTotal + $invTotal + $finTotal;
        $cashReconciliationDifference = $endingCash - $beginningCash - $netCashFlow;

        return [
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'plTree' => $plTree,
            'plTotal' => $plTotal,
            'nonCashTree' => $nonCashTree,
            'nonCashTotal' => $nonCashTotal,
            'opAssetsTree' => $opAssetsTree,
            'opAssetsTotal' => $opAssetsTotal,
            'opLiabTree' => $opLiabTree,
            'opLiabTotal' => $opLiabTotal,
            'invTree' => $invTree,
            'invTotal' => $invTotal,
            'finTree' => $finTree,
            'finTotal' => $finTotal,
            'beginningCash' => $beginningCash,
            'endingCash' => $endingCash,
            'netCashFlow' => $netCashFlow,
            'cashReconciliationDifference' => $cashReconciliationDifference,
            'operatingTotal' => $operatingTotal,
        ];
    }

    private function buildBaseTree(Collection $accounts)
    {
        $grouped = $accounts->groupBy('parent_id');
        $accounts->each(function ($item) use ($grouped) {
            $item->children = $grouped->get($item->id, collect());
        });

        return $accounts->whereNull('parent_id')->sortBy('code')->values();
    }

    private function cloneAndFilter($nodes, $leafCondition)
    {
        $filtered = collect();
        foreach ($nodes as $node) {
            $cloned = clone $node;
            if ($cloned->children->isNotEmpty()) {
                $cloned->children = $this->cloneAndFilter($node->children, $leafCondition);
            }

            if (($cloned->is_header && $cloned->children->isNotEmpty()) || (! $cloned->is_header && $cloned->calculated_balance != 0 && $leafCondition($cloned))) {
                $filtered->push($cloned);
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
                if ($node->is_header) {
                    $node->calculated_balance = $childTotal;
                }
            }
            $total += $node->calculated_balance;
        }

        return $total;
    }
}
