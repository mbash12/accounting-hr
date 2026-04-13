<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use UnitEnum;
use BackedEnum;
use Illuminate\Support\Collection;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class CashFlow extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.cash-flow';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Keuangan';

    protected static ?string $navigationLabel = 'Arus Kas';

    protected static ?string $title = 'Laporan Arus Kas';

    public function getTitle(): string
    {
        return 'Laporan Arus Kas';
    }

    public function getHeading(): string
    {
        return 'Laporan Arus Kas';
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
            ->label('Dari Tanggal')
            ->required()
            ->default(now()->startOfMonth()),

            DatePicker::make('end_date')
            ->label('Sampai Tanggal')
            ->required()
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

        if (isset($reportData['error']) || !$reportData['company']) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.cash-flow-pdf', $reportData);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Arus_Kas_' . now()->format('Ymd') . '.pdf');
    }

    public function getReportData(): array
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->data['end_date'] ?? now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'error' => 'Silakan pilih perusahaan tertentu.',
                'company' => null,
            ];
        }

        $company = Company::find($companyId);
        $allAccounts = Account::where('company_id', $companyId)->get();

        $movements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate, $companyId) {
            $q->where('company_id', $companyId);
            $q->whereBetween('date', [$startDate, $endDate]);
            $q->where('is_posted', true);
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

        // P&L accounts for net income (exclude depreciation 7xx with cash_flow=undefined — go into add-backs)
        $plCondition = fn($a) => in_array(substr($a->code, 0, 1), ['4', '5', '6', '8', '9'])
        || (substr($a->code, 0, 1) === '7' && $a->cash_flow !== 'undefined');
        $opAssetsCondition = fn($a) => $a->account_type === 'current_asset' && !$a->is_cash_bank;
        $opLiabilitiesCondition = fn($a) => $a->account_type === 'current_liability';
        $invCondition = fn($a) => in_array($a->account_type, ['fixed_asset', 'other_asset']);
        $finCondition = fn($a) => in_array($a->account_type, ['long_term_liability', 'equity']);

        // Non-cash adjustments: depreciation/amortisation (code 7xx, cash_flow=undefined)
        $nonCashCondition = fn($a) => substr($a->code, 0, 1) === '7' && $a->cash_flow === 'undefined';

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
            $q->whereDate('date', '<', $startDate);
            $q->where('is_posted', true);
        })
            ->first();

        $beginningCash = $allAccounts->where('is_cash_bank', true)->sum('opening_balance') + ($beginningCashMovements->total_debit ?? 0) - ($beginningCashMovements->total_credit ?? 0);

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

        $endingCash = $allAccounts->where('is_cash_bank', true)->sum('opening_balance') + ($endingCashMovements->total_debit ?? 0) - ($endingCashMovements->total_credit ?? 0);

        $operatingTotal = $plTotal + $nonCashTotal + $opAssetsTotal + $opLiabTotal;
        $netCashFlow = $operatingTotal + $invTotal + $finTotal;

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

            if (($cloned->is_header && $cloned->children->isNotEmpty()) || (!$cloned->is_header && $cloned->calculated_balance != 0 && $leafCondition($cloned))) {
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