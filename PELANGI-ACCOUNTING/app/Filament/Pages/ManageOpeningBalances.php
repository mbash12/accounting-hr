<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\OpeningBalance;
use App\Imports\OpeningBalancesImport;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use UnitEnum;

class ManageOpeningBalances extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string|UnitEnum|null $navigationGroup = 'Buku Besar';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-opening-balances';

    public ?array $data = [];

    public array $openingBalanceData = [];
    
    public string $filterAccountType = '';
    
    public string $searchQuery = '';

    public function mount(): void
    {
        $this->form->fill();
        $this->loadOpeningBalanceData();
    }

    public function loadOpeningBalanceData(): void
    {
        $selectedCompanyId = session('selected_company_id');

        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            $this->openingBalanceData = [];
            return;
        }

        $query = Account::where('company_id', $selectedCompanyId)
            ->where('is_header', false)
            ->where('is_active', true)
            ->orderBy('code');
        
        // Apply account type filter
        if ($this->filterAccountType) {
            $query->where('account_type', $this->filterAccountType);
        }
        
        // Apply search filter
        if ($this->searchQuery) {
            $search = '%' . $this->searchQuery . '%';
            $query->where(function ($q) use ($search) {
                $q->where('code', 'ilike', $search)
                  ->orWhere('name', 'ilike', $search);
            });
        }

        $accounts = $query->get();

        $openingBalances = OpeningBalance::where('company_id', $selectedCompanyId)
            ->get()
            ->keyBy('account_id');

        $this->openingBalanceData = $accounts->map(function ($account) use ($openingBalances) {
            $balance = $openingBalances->get($account->id);

            return [
                'id' => $balance->id ?? null,
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'account_type' => $account->account_type,
                'debit_amount' => ($balance && $balance->balance_type === 'debit') ? (float) $balance->amount : 0,
                'credit_amount' => ($balance && $balance->balance_type === 'credit') ? (float) $balance->amount : 0,
            ];
        })->toArray();
    }
    
    public function updatedFilterAccountType(): void
    {
        $this->loadOpeningBalanceData();
    }
    
    public function updatedSearchQuery(): void
    {
        $this->loadOpeningBalanceData();
    }
    
    public function getAccountTypes(): array
    {
        $selectedCompanyId = session('selected_company_id');
        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            return [];
        }
        
        return Account::where('company_id', $selectedCompanyId)
            ->whereNotNull('account_type')
            ->distinct()
            ->pluck('account_type', 'account_type')
            ->toArray();
    }
    
    public function getTotalDebit(): float
    {
        return collect($this->openingBalanceData)->sum(fn($item) => (float) ($item['debit_amount'] ?? 0));
    }

    public function getTotalCredit(): float
    {
        return collect($this->openingBalanceData)->sum(fn($item) => (float) ($item['credit_amount'] ?? 0));
    }
    
    public function getDifference(): float
    {
        return $this->getTotalDebit() - $this->getTotalCredit();
    }

    public function saveOpeningBalances(): void
    {
        $selectedCompanyId = session('selected_company_id');

        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            Notification::make()
                ->danger()
                ->title(__('Error'))
                ->body(__('Please select a company first'))
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            $openingDate = null;

            foreach ($this->openingBalanceData as $row) {
                $debitAmount = floatval($row['debit_amount'] ?? 0);
                $creditAmount = floatval($row['credit_amount'] ?? 0);

                if ($debitAmount > 0 && $creditAmount > 0) {
                    continue; // Skip invalid rows
                }

                if ($debitAmount > 0) {
                    $balance = OpeningBalance::updateOrCreate(
                        [
                            'account_id' => $row['account_id'],
                            'company_id' => $selectedCompanyId,
                        ],
                        [
                            'balance_type' => 'debit',
                            'amount' => $debitAmount,
                            'date' => now()->startOfYear()->format('Y-m-d'),
                            'description' => __('Opening balance for :account', ['account' => $row['account_name']]),
                            'created_by_user_id' => auth()->id(),
                        ]
                    );
                    $openingDate = $balance->date;
                } elseif ($creditAmount > 0) {
                    $balance = OpeningBalance::updateOrCreate(
                        [
                            'account_id' => $row['account_id'],
                            'company_id' => $selectedCompanyId,
                        ],
                        [
                            'balance_type' => 'credit',
                            'amount' => $creditAmount,
                            'date' => now()->startOfYear()->format('Y-m-d'),
                            'description' => __('Opening balance for :account', ['account' => $row['account_name']]),
                            'created_by_user_id' => auth()->id(),
                        ]
                    );
                    $openingDate = $balance->date;
                } else {
                    // Remove opening balance if both amounts are 0
                    OpeningBalance::where('account_id', $row['account_id'])
                        ->where('company_id', $selectedCompanyId)
                        ->delete();
                }
            }

            // Create journal entry for opening balances
            $this->createOpeningBalanceJournal($selectedCompanyId, $openingDate ?? now()->startOfYear()->format('Y-m-d'));

            DB::commit();

            $this->loadOpeningBalanceData();

            Notification::make()
                ->success()
                ->title(__('Success'))
                ->body(__('Opening balances have been saved successfully'))
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title(__('Error'))
                ->body(__('Failed to save opening balances: :message', ['message' => $e->getMessage()]))
                ->send();
        }
    }

    protected function createOpeningBalanceJournal(int $companyId, $date): void
    {
        $openingBalances = OpeningBalance::where('company_id', $companyId)->get();

        if ($openingBalances->isEmpty()) {
            // Delete existing opening balance journal if no balances
            $existingJournal = \App\Models\JournalEntry::where('company_id', $companyId)
                ->where('sub_module', 'opening_balance')
                ->first();
            if ($existingJournal) {
                $existingJournal->items()->forceDelete();
                $existingJournal->forceDelete();
            }
            return;
        }

        $totalDebit = $openingBalances->where('balance_type', 'debit')->sum('amount');
        $totalCredit = $openingBalances->where('balance_type', 'credit')->sum('amount');
        $difference = $totalDebit - $totalCredit;

        // Find or create journal entry
        $journalEntry = \App\Models\JournalEntry::updateOrCreate(
            [
                'company_id' => $companyId,
                'sub_module' => 'opening_balance',
            ],
            [
                'entry_number' => $this->generateOpeningBalanceEntryNumber($companyId),
                'date' => $date,
                'description' => 'Saldo Awal / Opening Balance',
                'amount' => $totalDebit,
                'total_amount' => $totalDebit,
                'status' => 'posted',
                'is_posted' => true,
                'reference_type' => OpeningBalance::class,
                'reference_id' => $openingBalances->first()->id,
                'posted_by_user_id' => auth()->id(),
                'posted_at' => now(),
                'created_by_user_id' => auth()->id(),
                'updated_by_user_id' => auth()->id(),
            ]
        );

        // Delete existing items
        $journalEntry->items()->delete();

        // Create journal items for each opening balance
        foreach ($openingBalances as $balance) {
            \App\Models\JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $balance->account_id,
                'debit' => $balance->balance_type === 'debit' ? $balance->amount : 0,
                'credit' => $balance->balance_type === 'credit' ? $balance->amount : 0,
                'notes' => $balance->description,
            ]);
        }

        // Add balancing entry if there's a difference (Opening Balance Equity)
        if (abs($difference) > 0.01) {
            $equityAccount = $this->getOpeningBalanceEquityAccount($companyId);
            if ($equityAccount) {
                \App\Models\JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $equityAccount->id,
                    'debit' => $difference < 0 ? abs($difference) : 0,
                    'credit' => $difference > 0 ? $difference : 0,
                    'notes' => 'Selisih Saldo Awal / Opening Balance Equity',
                ]);
            }
        }
    }

    protected function generateOpeningBalanceEntryNumber(int $companyId): string
    {
        $existing = \App\Models\JournalEntry::where('company_id', $companyId)
            ->where('sub_module', 'opening_balance')
            ->first();

        if ($existing) {
            return $existing->entry_number;
        }

        return 'OB-' . now()->startOfYear()->format('Ymd') . '-0001';
    }

    protected function getOpeningBalanceEquityAccount(int $companyId): ?Account
    {
        // Try to find Opening Balance Equity account by common codes/names
        $account = Account::where('company_id', $companyId)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('code', 'like', '%3900%')
                    ->orWhere('code', 'like', '%390%')
                    ->orWhere('name', 'like', '%Opening Balance%')
                    ->orWhere('name', 'like', '%Saldo Awal%')
                    ->orWhere('name', 'like', '%Retained Earnings%')
                    ->orWhere('name', 'like', '%Laba Ditahan%');
            })
            ->first();

        // Fallback: find any equity account (code starting with 3)
        if (!$account) {
            $account = Account::where('company_id', $companyId)
                ->where('is_active', true)
                ->where('is_header', false)
                ->where('code', 'like', '3%')
                ->orderBy('code')
                ->first();
        }

        return $account;
    }

    public function updateOpeningBalanceRow($index, $field, $value): void
    {
        if (isset($this->openingBalanceData[$index])) {
            // Ensure debit_amount and credit_amount are properly cast to float when updating
            if ($field === 'debit_amount' || $field === 'credit_amount') {
                $this->openingBalanceData[$index][$field] = (float) $value;
            } else {
                $this->openingBalanceData[$index][$field] = $value;
            }
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('Opening Balance');
    }

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public function getTitle(): string
    {
        return __('Opening Balance');
    }

    public function getSubheading(): ?string
    {
        $selectedCompanyId = session('selected_company_id');

        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $company = \App\Models\Company::find($selectedCompanyId);
            return $company ? $company->name : null;
        }

        return __('All Companies');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveOpeningBalances')
                ->label(__('Save Opening Balance'))
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(function () {
                    $selectedCompanyId = session('selected_company_id');
                    return $selectedCompanyId && $selectedCompanyId !== 'all';
                })
                ->action(function () {
                    $this->saveOpeningBalances();
                }),

            Action::make('importOpeningBalances')
                ->label(__('Import Opening Balance'))
                ->icon('heroicon-o-document-arrow-up')
                ->color('primary')
                ->visible(function () {
                    $selectedCompanyId = session('selected_company_id');
                    return $selectedCompanyId && $selectedCompanyId !== 'all';
                })
                ->form([
                    FileUpload::make('file')
                        ->label(__('File Excel'))
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required()
                        ->helperText(__('Upload Excel file with columns: account_code, account_name, debit_amount, credit_amount')),
                ])
                ->modalDescription(__('Upload Excel file with opening balance information. You can download the template below to see the expected format.'))
                ->modalFooterActions(fn ($action) => [
                    $action->getModalSubmitAction(),
                    \Filament\Actions\Action::make('download_template')
                        ->label(__('Download Template'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(function () {
                            return Excel::download(
                                new \App\Exports\OpeningBalancesTemplateExport(),
                                'opening-balance-import-template.xlsx'
                            );
                        }),
                    $action->getModalCancelAction(),
                ])
                ->action(function (array $data) {
                    $selectedCompanyId = session('selected_company_id');
                    if (!$selectedCompanyId || $selectedCompanyId === 'all') {
                        Notification::make()
                            ->danger()
                            ->title(__('Error'))
                            ->body(__('Please select a company first'))
                            ->send();
                        return;
                    }

                    try {
                        $filePath = Storage::disk('local')->path($data['file']);
                        Excel::import(new OpeningBalancesImport, $filePath);

                        $this->createOpeningBalanceJournal($selectedCompanyId, now()->startOfYear()->format('Y-m-d'));

                        Notification::make()
                            ->success()
                            ->title(__('Success'))
                            ->body(__('Opening balances imported successfully'))
                            ->send();

                        $this->loadOpeningBalanceData();
                        $this->dispatch('$refresh');

                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title(__('Import Failed'))
                            ->body(__('Error: :message', ['message' => $e->getMessage()]))
                            ->send();
                    }
                })
                ->modalWidth('2xl'),
        ];
    }

    public function getOpeningBalances()
    {
        $selectedCompanyId = session('selected_company_id');

        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            return OpeningBalance::with('account')
                ->where('company_id', $selectedCompanyId)
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return collect();
    }

    protected function getFormSchema(): array
    {
        return [];
    }
}