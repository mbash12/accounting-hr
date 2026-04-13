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
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

use UnitEnum;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class IncomeStatement extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.income-statement';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Keuangan';

    protected static ?string $navigationLabel = 'Laba Rugi';

    protected static ?string $title = 'Income Statement';

    public function getTitle(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function getHeading(): string
    {
        return 'Laporan Laba Rugi';
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
        }, 'Laporan_Laba_Rugi_' . now()->format('Ymd') . '.pdf');
    }

    protected function getViewData(): array
    {
        return $this->getRawData();
    }

    protected function getRawData(): array
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->data['end_date'] ?? now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'revenues' => collect(),
                'expenses' => collect(),
                'netIncome' => 0,
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Silakan pilih perusahaan tertentu dari pemilih global untuk melihat laporan.'
            ];
        }

        $accounts = Account::where('company_id', $companyId)
            ->whereIn(DB::raw('SUBSTR(code, 1, 1)'), ['4', '5', '6', '7', '8', '9'])
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
        })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // Calculate balances
        foreach ($accounts as $account) {
            $movement = $movements->get($account->id);
            $debit = $movement ? $movement->total_debit : 0;
            $credit = $movement ? $movement->total_credit : 0;

            $root = substr($account->code, 0, 1);

            // For Income Statement, we typically do not include opening_balance because it is a period report.
            if (in_array($root, ['5', '6', '7', '9'])) {
                // Expenses mostly debit
                $account->calculated_balance = $debit - $credit;
            }
            else {
                // Revenues mostly credit
                $account->calculated_balance = $credit - $debit;
            }
        }

        // Build Tree
        $accountTree = $this->buildTree($accounts);

        // Calculate hierarchical balances
        $this->aggregateBalances($accountTree);

        // Filter out zero nodes
        $accountTree = $this->filterZeroBalanceAccounts($accountTree);

        // Split into main sections
        $operatingRevenues = $accountTree->filter(fn($a) => substr($a->code, 0, 1) === '4');
        $costOfGoodsSold = $accountTree->filter(fn($a) => substr($a->code, 0, 1) === '5');
        $operatingExpenses = $accountTree->filter(fn($a) => substr($a->code, 0, 1) === '6');
        $otherRevenues = $accountTree->filter(fn($a) => substr($a->code, 0, 1) === '8');
        $otherExpenses = $accountTree->filter(fn($a) => in_array(substr($a->code, 0, 1), ['7', '9']));

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

    private function filterZeroBalanceAccounts($nodes)
    {
        $filteredNodes = collect();

        foreach ($nodes as $node) {
            // Process children first
            if ($node->children->isNotEmpty()) {
                $node->children = $this->filterZeroBalanceAccounts($node->children);
            }

            // Include the node if:
            // 1. It's a header (even if balance is 0, but has children)
            // 2. It's not a header and has a non-zero balance
            if (($node->is_header && $node->children->isNotEmpty()) || (!$node->is_header && $node->calculated_balance != 0)) {
                $filteredNodes->push($node);
            }
        }

        return $filteredNodes;
    }
}