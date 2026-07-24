<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class GeneralLedger extends Page implements HasForms
{
    use HasPageShield, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.general-ledger';

    protected static string|UnitEnum|null $navigationGroup = 'Financial Reports';

    protected static ?string $navigationLabel = 'General Ledger';

    protected static ?string $title = 'General Ledger';

    public function getTitle(): string
    {
        return 'General Ledger';
    }

    public function getHeading(): string
    {
        return 'General Ledger';
    }

    public ?array $data = [];

    public function mount(): void
    {
        // Accept deep-link parameters so other reports can drill into a
        // pre-filtered Buku Besar view (e.g. "show me account X for Y-Z").
        $accountIds = request()->query('account_ids', []);
        if (! is_array($accountIds)) {
            $accountIds = filled($accountIds) ? [$accountIds] : [];
        }
        $accountIds = array_values(array_filter(array_map('intval', $accountIds)));

        $this->form->fill([
            'select_all' => false,
            'account_ids' => $accountIds,
            'start_date' => request()->query('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => request()->query('end_date', now()->format('Y-m-d')),
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Toggle::make('select_all')
                    ->label('Select All Accounts')
                    ->reactive()
                    ->inline(false)
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $set('account_ids', []);
                        }
                    }),

                Select::make('account_ids')
                    ->label('Accounts')
                    ->multiple()
                    ->options(function () {
                        $companyId = session('selected_company_id');

                        if (! $companyId || $companyId === 'all') {
                            return [];
                        }

                        return Account::where('company_id', $companyId)
                            ->where('is_header', false)
                            ->orderBy('code')
                            ->get()
                            ->mapWithKeys(function ($account) {
                                return [$account->id => "{$account->code} - {$account->name}"];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->optionsLimit(1000)
                    ->required(fn ($get) => ! $get('select_all'))
                    ->disabled(fn ($get) => $get('select_all')),

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
            ->columns(4)
            ->statePath('data');
    }

    public function filterReport(): void
    {
        $this->validate();
    }

    public function downloadPdf()
    {
        $reportData = $this->getReportData();

        if (isset($reportData['error']) || ! $reportData['company']) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.general-ledger-pdf', $reportData)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'General_Ledger_'.now()->format('Ymd').'.pdf');
    }

    public function getReportData(): array
    {
        $accountIds = $this->data['account_ids'] ?? [];
        if (! is_array($accountIds)) {
            $accountIds = filled($accountIds) ? [$accountIds] : [];
        }
        $selectAll = (bool) ($this->data['select_all'] ?? false);
        $startDate = filled($this->data['start_date'] ?? null)
            ? $this->data['start_date']
            : now()->startOfMonth()->format('Y-m-d');
        $endDate = filled($this->data['end_date'] ?? null)
            ? $this->data['end_date']
            : now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (! $companyId || $companyId === 'all') {
            return [
                'accounts_data' => collect(),
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Please select a specific company from the global selector to view the report.',
            ];
        }

        if (! $selectAll && empty($accountIds)) {
            return [
                'accounts_data' => collect(),
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Please select at least one account to view the General Ledger report.',
            ];
        }

        $company = Company::find($companyId);

        if ($selectAll) {
            $accounts = Account::withTrashed()->where('company_id', $companyId)->where('is_header', false)->orderBy('code')->get();
        } else {
            $accounts = Account::withTrashed()->where('company_id', $companyId)
                ->where('is_header', false)
                ->whereIn('id', $accountIds)
                ->orderBy('code')
                ->get();
        }

        if ($accounts->isEmpty()) {
            return [
                'accounts_data' => collect(),
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Accounts not found.',
            ];
        }

        $accountsData = collect();
        $yearStart = \Carbon\Carbon::parse($startDate)->startOfYear()->format('Y-m-d');
        $hasPostedOpeningJournal = \App\Models\JournalEntry::query()
            ->where('company_id', $companyId)
            ->where('sub_module', 'opening_balance')
            ->where('is_posted', true)
            ->exists();

        foreach ($accounts as $account) {
            $accountId = $account->id;
            $isDebitNormal = $account->isDebitNormal();
            $isNominal = $account->isRevenueAccount() || $account->isExpenseAccount();

            $baseOpeningBalance = $account->reportOpeningBalance($hasPostedOpeningJournal);

            $priorMovementsQuery = JournalEntryItem::where('account_id', $accountId)
                ->whereHas('journalEntry', function ($q) use ($companyId, $startDate, $yearStart, $isNominal) {
                    $q->where('company_id', $companyId)
                        ->where('is_posted', true);

                    if ($isNominal) {
                        $q->whereDate('date', '>=', $yearStart)
                            ->whereDate('date', '<', $startDate);
                    } else {
                        $q->where(function ($subQ) use ($startDate) {
                            $subQ->whereDate('date', '<', $startDate)
                                ->orWhere(function ($obQ) use ($startDate) {
                                    $obQ->whereDate('date', '=', $startDate)
                                        ->where('sub_module', 'opening_balance');
                                });
                        });
                    }
                })
                ->select(DB::raw('SUM(debit) as total_debit, SUM(credit) as total_credit'))
                ->first();

            $priorDebit = $priorMovementsQuery ? (float) $priorMovementsQuery->total_debit : 0;
            $priorCredit = $priorMovementsQuery ? (float) $priorMovementsQuery->total_credit : 0;

            if ($isNominal) {
                $openingBalance = $isDebitNormal ? ($priorDebit - $priorCredit) : ($priorCredit - $priorDebit);
            } else {
                $openingBalance = $isDebitNormal ? ($baseOpeningBalance + $priorDebit - $priorCredit) : ($baseOpeningBalance + $priorCredit - $priorDebit);
            }

            $items = JournalEntryItem::with('journalEntry')
                ->where('account_id', $accountId)
                ->whereHas('journalEntry', function ($q) use ($companyId, $startDate, $endDate) {
                    $q->where('company_id', $companyId)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->where('is_posted', true)
                        ->where(function ($query) {
                            $query->whereNull('sub_module')
                                ->orWhere('sub_module', '!=', 'opening_balance');
                        });
                })
                ->join('journal_entries', 'journal_entry_items.journal_entry_id', '=', 'journal_entries.id')
                ->orderBy('journal_entries.date')
                ->orderBy('journal_entries.entry_number')
                ->select('journal_entry_items.*')
                ->get();

            $rows = collect();
            $runningBalance = $openingBalance;

            foreach ($items as $item) {
                $debit = (float) $item->debit;
                $credit = (float) $item->credit;

                if ($isDebitNormal) {
                    $runningBalance = $runningBalance + $debit - $credit;
                } else {
                    $runningBalance = $runningBalance + $credit - $debit;
                }

                $rows->push([
                    'date' => $item->journalEntry->date->format('d M Y'),
                    'source_no' => $item->journalEntry->entry_number ?? $item->journalEntry->reference_no,
                    'source_url' => \App\Support\ReportDrilldown::sourceDocumentUrl($item->journalEntry),
                    'check_no' => '',
                    'description' => $item->notes ?? $item->journalEntry->description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $runningBalance,
                    'reconciled' => '-',
                ]);
            }

            $accountsData->push([
                'account' => $account,
                'opening_balance' => $openingBalance,
                'rows' => $rows,
            ]);
        }

        return [
            'accounts_data' => $accountsData,
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
