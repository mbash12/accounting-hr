<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

use UnitEnum;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class BalanceSheet extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.balance-sheet';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Keuangan';

    protected static ?string $navigationLabel = 'Neraca';

    protected static ?string $title = 'Balance Sheet';

    public function getTitle(): string
    {
        return 'Laporan Neraca';
    }

    public function getHeading(): string
    {
        return 'Laporan Neraca';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'date' => now()->format('Y-m-d'),
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
            DatePicker::make('date')
            ->label('Tanggal')
            ->required()
            ->default(now())
            ->reactive(false) // Disable reactive behavior
            ->lazy(false) // Disable lazy loading
            ->afterStateUpdated(fn($state, $set) => null) // Don't update anything on change
            ->suffixAction(function () {
            return \Filament\Actions\Action::make('filter_date')
                ->icon('heroicon-m-funnel')
                ->action('filterReport')
                ->color('primary');
        }),
        ])
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

        $pdf = Pdf::loadView('filament.pages.reports.balance-sheet-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Neraca_' . now()->format('Y-m-d') . '.pdf');
    }

    protected function getViewData(): array
    {
        return $this->getRawData();
    }

    protected function getRawData(): array
    {
        $date = $this->data['date'] ?? now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'assets' => collect(),
                'liabilities' => collect(),
                'equity' => collect(),
                'netIncome' => 0,
                'company' => null,
                'date' => $date,
                'error' => 'Please select a specific company from the global selector to view the report.'
            ];
        }

        $accounts = Account::where('company_id', $companyId)
            ->orderBy('code')
            ->get();

        $yearStart = \Carbon\Carbon::parse($date)->startOfYear()->format('Y-m-d');

        // prior year transactions (to compute Retained Earnings)
        $priorYearMovements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($yearStart, $companyId) {
            $q->where('company_id', $companyId);
            $q->whereDate('date', '<', $yearStart);
            $q->where('is_posted', true);
        })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // all transactions up to $date
        $allMovements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($date, $companyId) {
            $q->where('company_id', $companyId);
            $q->whereDate('date', '<=', $date);
            $q->where('is_posted', true);
        })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        $priorYearNetIncome = 0;
        $allTimeNetIncome = 0;

        // Calculate balances
        foreach ($accounts as $account) {
            $root = substr($account->code, 0, 1);
            $opening = $account->opening_balance ?? 0;
            $isDebitNormal = in_array($root, ['1', '5', '6', '7', '9']);

            $mov = $allMovements->get($account->id);
            $debit = $mov ? $mov->total_debit : 0;
            $credit = $mov ? $mov->total_credit : 0;

            if ($isDebitNormal) {
                $account->calculated_balance = $opening + $debit - $credit;
            }
            else {
                $account->calculated_balance = $opening + $credit - $debit;
            }

            // Categorize nominal accounts for Net Income calculation
            if (in_array($root, ['4', '5', '6', '7', '8', '9'])) {
                if ($isDebitNormal) {
                    $allTimeNetIncome -= $account->calculated_balance;
                }
                else {
                    $allTimeNetIncome += $account->calculated_balance;
                }

                $priorMov = $priorYearMovements->get($account->id);
                $priorD = $priorMov ? $priorMov->total_debit : 0;
                $priorC = $priorMov ? $priorMov->total_credit : 0;
                $priorBalance = $isDebitNormal ? ($opening + $priorD - $priorC) : ($opening + $priorC - $priorD);

                if ($isDebitNormal) {
                    $priorYearNetIncome -= $priorBalance;
                }
                else {
                    $priorYearNetIncome += $priorBalance;
                }
            }
        }

        $currentYearNetIncome = $allTimeNetIncome - $priorYearNetIncome;

        // Build Tree
        $accountTree = $this->buildTree($accounts);

        // Inject Net Income and Retained Earnings into Equity
        $equityRoot = $accountTree->first(fn($a) => str_starts_with($a->code, '3'));

        if ($equityRoot) {
            if ($priorYearNetIncome != 0) {
                $reAccount = new Account();
                $reAccount->name = 'Laba Ditahan Sebelumnya (Retained Earnings)';
                $reAccount->code = '';
                $reAccount->is_header = false;
                $reAccount->calculated_balance = $priorYearNetIncome;
                $reAccount->children = collect();

                $equityRoot->children->push($reAccount);
            }

            $netIncomeAccount = new Account();
            $netIncomeAccount->name = 'Laba Tahun Berjalan (Current Earnings)';
            $netIncomeAccount->code = '';
            $netIncomeAccount->is_header = false;
            $netIncomeAccount->calculated_balance = $currentYearNetIncome;
            $netIncomeAccount->children = collect();

            $equityRoot->children->push($netIncomeAccount);
        }

        // 3. Aggregate Balance Sheet Roots
        $bsRoots = $accountTree->filter(fn($a) => in_array(substr($a->code, 0, 1), ['1', '2', '3']));
        $this->aggregateBalances($bsRoots);

        // Filter out accounts with zero balance (except headers that have children)
        $bsRoots = $this->filterZeroBalanceAccounts($bsRoots);

        // Filter for Main Sections
        $assets = $bsRoots->filter(fn($a) => str_starts_with($a->code, '1'));
        $liabilities = $bsRoots->filter(fn($a) => str_starts_with($a->code, '2'));
        $equity = $bsRoots->filter(fn($a) => str_starts_with($a->code, '3'));

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'netIncome' => $currentYearNetIncome,
            'company' => Company::find($companyId),
            'date' => $date,
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