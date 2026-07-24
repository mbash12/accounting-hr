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

class AccountBalances extends Page implements HasForms
{
    use HasPageShield, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.account-balances';

    protected static string|UnitEnum|null $navigationGroup = 'Financial Reports';

    protected static ?string $navigationLabel = 'Account Balances';

    protected static ?string $title = 'Account Balances';

    public function getTitle(): string
    {
        return 'Account Balances';
    }

    public function getHeading(): string
    {
        return 'Account Balances';
    }

    public ?array $data = [];

    public $search = '';

    public array $filteredAccountIds = [];

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
                    ->label('Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->validate())
                    ->default(now())
                    ->reactive(false)
                    ->lazy(false)
                    ->suffixAction(function () {
                        return \Filament\Actions\Action::make('filter_date')
                            ->icon('heroicon-m-funnel')
                            ->action('filterReport')
                            ->color('primary');
                    }),
            ])
            ->statePath('data');
    }

    public function filterReport()
    {
        $this->validate();
    }

    public function downloadPdf()
    {
        $accounts = $this->getAccounts();
        $date = filled($this->data['date'] ?? null)
            ? $this->data['date']
            : now()->format('Y-m-d');
        $companyId = session('selected_company_id');
        $company = Company::find($companyId);

        if (! $company || $accounts->isEmpty()) {
            return;
        }

        $data = [
            'accounts' => $accounts,
            'company' => $company,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('filament.pages.reports.account-balances-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Account_Balances_'.now()->format('Y-m-d').'.pdf');
    }

    public function getAccounts()
    {
        $date = filled($this->data['date'] ?? null)
            ? $this->data['date']
            : now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (! $companyId || $companyId === 'all') {
            return collect();
        }

        $query = Account::withTrashed()->where('company_id', $companyId);

        // First get all accounts without eager loading children to avoid circular references
        $allAccounts = $query->get();
        $hasPostedOpeningJournal = \App\Models\JournalEntry::query()
            ->where('company_id', $companyId)
            ->where('sub_module', 'opening_balance')
            ->where('is_posted', true)
            ->exists();

        // Eagerly calculate balances for all accounts
        $movements = JournalEntryItem::select(
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

        foreach ($allAccounts as $account) {
            $movement = $movements->get($account->id);
            $debit = $movement ? $movement->total_debit : 0;
            $credit = $movement ? $movement->total_credit : 0;

            $opening = $account->reportOpeningBalance($hasPostedOpeningJournal);
            $account->calculated_balance = $account->balanceFromMovements($debit, $credit, $opening);
        }

        // Apply search filter if search term is provided
        if (! empty($this->search)) {
            $searchTerm = '%'.strtolower($this->search).'%';

            // Filter initially matched accounts
            $matchedIds = $allAccounts->filter(function ($account) use ($searchTerm) {
                return str_contains(strtolower($account->code), str_replace('%', '', $searchTerm)) ||
                str_contains(strtolower($account->name), str_replace('%', '', $searchTerm)) ||
                str_contains(strtolower($account->description ?? ''), str_replace('%', '', $searchTerm));
            })->pluck('id')->toArray();

            if (! empty($matchedIds)) {
                $parentIds = $this->getAllParentIds($matchedIds, $allAccounts);
                $allIds = array_unique(array_merge($matchedIds, $parentIds));

                $this->filteredAccountIds = $allIds;

                $allAccounts = $allAccounts->filter(function ($account) use ($allIds) {
                    return in_array($account->id, $allIds);
                });
            } else {
                $this->filteredAccountIds = [];

                return collect();
            }
        } else {
            $this->filteredAccountIds = [];
        }

        // Build Tree and calculate header balances
        $accountTree = $this->buildTree($allAccounts);

        // Return only root accounts
        return $accountTree;
    }

    private function buildTree(Collection $accounts)
    {
        $grouped = $accounts->groupBy('parent_id');
        $accounts->each(function ($item) use ($grouped) {
            $children = $grouped->get($item->id, collect());
            $item->children = $children->filter(function ($child) use ($item) {
                return $child->id !== $item->id;
            });
        });

        $roots = $accounts->whereNull('parent_id');
        $this->aggregateBalances($roots);

        return $roots->sortBy('code');
    }

    private function aggregateBalances($nodes)
    {
        $total = 0;
        foreach ($nodes as $node) {
            if (isset($node->children) && $node->children->isNotEmpty()) {
                $childTotal = $this->aggregateBalances($node->children);
                if ($node->is_header) {
                    $node->calculated_balance = $childTotal;
                }
            }
            $total += $node->calculated_balance;
        }

        return $total;
    }

    private function getAllParentIds(array $accountIds, Collection $allAccounts): array
    {
        $parentIds = [];
        $processedIds = [];

        while (! empty($accountIds)) {
            $currentIds = array_diff($accountIds, $processedIds);
            if (empty($currentIds)) {
                break;
            }

            $parents = $allAccounts->whereIn('id', $currentIds)
                ->whereNotNull('parent_id')
                ->pluck('parent_id')
                ->toArray();

            $parentIds = array_merge($parentIds, $parents);
            $processedIds = array_merge($processedIds, $currentIds);
            $accountIds = $parents;
        }

        return array_unique($parentIds);
    }
}
