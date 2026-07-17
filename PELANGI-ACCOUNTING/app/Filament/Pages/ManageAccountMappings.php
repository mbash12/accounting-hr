<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\AccountMapping;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use UnitEnum;

class ManageAccountMappings extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;
    protected static ?string $navigationLabel = 'Account Mapping';

    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Account Mapping & Journal';

    public static function getNavigationLabel(): string
    {
        return __('Account Mapping');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public function getTitle(): string
    {
        return __('Account Mapping & Journal');
    }

    protected string $view = 'filament.pages.manage-account-mappings';

    public $selectedDocumentType = null;

    public array $allMappings = [];

    public array $originalMappings = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('auto_map')
                ->label('Auto Map')
                ->icon('heroicon-o-sparkles')
                ->color('gray')
                ->action('autoMapAccounts')
                ->requiresConfirmation()
                ->modalHeading('Auto Map Accounts')
                ->modalDescription('The system will automatically find and map accounts based on standard account codes. Existing mappings will be replaced.')
                ->modalSubmitActionLabel('Yes, Auto Map')
                ->disabled(fn () => !session('selected_company_id') || session('selected_company_id') === 'all'),
            Action::make('save')
                ->label('Save All')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('saveAllMappings')
                ->disabled(fn () => !session('selected_company_id') || session('selected_company_id') === 'all'),
        ];
    }

    public function mount(): void
    {
        if (empty($this->selectedDocumentType)) {
            $documentTypes = array_keys($this->getDocumentTypes());
            $this->selectedDocumentType = $documentTypes[0] ?? null;
        }

        $allowedTypes = array_keys($this->getDocumentTypes());
        if (!in_array($this->selectedDocumentType, $allowedTypes)) {
            $this->selectedDocumentType = $allowedTypes[0] ?? null;
        }

        $this->loadAllMappings();
        $this->originalMappings = $this->allMappings;
    }

    public function selectDocumentType($type): void
    {
        $this->selectedDocumentType = $type;
    }

    public function hasChanges($documentType = null): bool
    {
        if ($documentType) {
            return ($this->allMappings[$documentType] ?? []) !== ($this->originalMappings[$documentType] ?? []);
        }
        return $this->allMappings !== $this->originalMappings;
    }

    public function hasFieldChanged($documentType, $mappingType): bool
    {
        $current = $this->allMappings[$documentType][$mappingType]['account_id'] ?? '';
        $original = $this->originalMappings[$documentType][$mappingType]['account_id'] ?? '';
        return $current !== $original;
    }

    protected function loadAllMappings(): void
    {
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            $this->allMappings = [];
            return;
        }

        $documentTypes = array_keys($this->getDocumentTypes());

        foreach ($documentTypes as $documentType) {
            $existingMappings = AccountMapping::where('company_id', $companyId)
                ->where('document_type', $documentType)
                ->get()
                ->keyBy('mapping_type');

            $applicableMappings = AccountMapping::DOCUMENT_MAPPING_TYPES[$documentType] ?? [];

            foreach ($applicableMappings as $mappingType) {
                $this->allMappings[$documentType][$mappingType] = [
                    'account_id' => $existingMappings->get($mappingType)?->account_id ? (string) $existingMappings->get($mappingType)->account_id : '',
                    'description' => $existingMappings->get($mappingType)?->description ?? null,
                ];
            }
        }
    }

    public function getCurrentMappings(): array
    {
        return $this->allMappings[$this->selectedDocumentType] ?? [];
    }

    public function getDocumentTypes(): array
    {
        $allTypes = AccountMapping::DOCUMENT_TYPES;

            $allowedTypes = [
            'delivery_document',
            'sales_invoice',
            'sales_return',
            'goods_receipt',
            'purchase_invoice',
            'purchase_return',
            'receivable_payment',
            'payable_payment',
            'payroll',
            'deferred_revenue',
            'period_closing',
            ];

            $types = array_intersect_key($allTypes, array_flip($allowedTypes));

            $translations = [
            'delivery_document' => 'Sales Delivery',
            'sales_invoice' => 'Sales Invoice',
            'sales_return' => 'Sales Return',
            'goods_receipt' => 'Goods Receipt',
            'purchase_invoice' => 'Purchase Invoice',
            'purchase_return' => 'Purchase Return',
            'receivable_payment' => 'Receivable Payment',
            'payable_payment' => 'Payable Payment',
            'payroll' => 'Payroll',
            'deferred_revenue' => 'Deferred Revenue',
            'period_closing' => 'Period Closing / Tutup Buku',
            ];

        return array_map(fn($key) => $translations[$key] ?? $types[$key], array_combine(array_keys($types), array_keys($types)));
    }

    public function getMappingTypes(): array
    {
        if (!$this->selectedDocumentType) {
            return [];
        }

        $mappingTypes = AccountMapping::DOCUMENT_MAPPING_TYPES[$this->selectedDocumentType] ?? [];

        $translations = [
            'sales' => 'Sales Revenue',
            'accounts_receivable' => 'Accounts Receivable',
            'discount' => 'Discount',
            'tax' => 'Tax',
            'other_charges' => 'Other Charges',
            'cogs' => 'Cost of Goods Sold',
            'inventory' => 'Inventory',
            'accounts_payable' => 'Accounts Payable',
            'purchases' => 'Purchases/Expenses',
            'sales_return' => 'Sales Return',
            'purchase_return' => 'Purchase Return',
            'advance_receivable' => 'Advance Receivable',
            'advance_payable' => 'Advance Payable',
            'grni' => 'GRNI (Goods Received Not Invoiced)',
            'cash' => 'Cash',
            'bank' => 'Bank',
            'expense' => 'Expense',
            'gain' => 'Other Income',
            'loss' => 'Other Expense',
            'write_off' => 'Write Off',
            'salary_expense' => 'Salary Expense',
            'thr_expense' => 'THR Expense',
            'bpjs_expense' => 'BPJS Expense (Company)',
            'salary_payable' => 'Salary Payable (Net)',
            'pph21_payable' => 'PPh21 Payable',
            'bpjs_payable' => 'BPJS Payable (Total)',
            'deferred_revenue_liability' => 'Deferred Revenue (Liability)',
            'deferred_revenue_recognition' => 'Deferred Revenue Recognition',
            'retained_earnings' => 'Retained Earnings / Laba Ditahan',
        ];

        return array_map(function ($mappingType) use ($translations) {
            return $translations[$mappingType] ?? $mappingType;
        }, array_combine($mappingTypes, $mappingTypes));
    }

    public function getMappingDescriptions(): array
    {
        return [
            'sales' => 'Sales/revenue account',
            'accounts_receivable' => 'Credit sales tracking',
            'discount' => 'Discounts given/received',
            'tax' => 'VAT/GST tax amounts',
            'other_charges' => 'Shipping, handling fees',
            'cogs' => 'Cost of inventory sold',
            'inventory' => 'Product stock value',
            'accounts_payable' => 'Credit purchases tracking',
            'sales_return' => 'Returned sales revenue',
            'purchase_return' => 'Returned purchases cost',
            'advance_receivable' => 'Customer prepayments',
            'advance_payable' => 'Supplier prepayments',
            'cash' => 'Physical cash transactions',
            'bank' => 'Bank balance transactions',
            'expense' => 'General expenses',
            'gain' => 'Additional income',
            'loss' => 'Losses/expenses',
            'write_off' => 'Write off uncollectible amount',
            'deferred_revenue_liability' => 'Customer prepayments (liability)',
            'deferred_revenue_recognition' => 'Revenue recognized from deferred',
            'grni' => 'Goods received not invoiced',
            'retained_earnings' => 'Equity account for year-end Tutup Buku net income',
        ];
    }

    public function getAccounts(): array
    {
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [];
        }

        return Account::where('company_id', $companyId)
            ->where('is_active', true)
            ->where('is_header', false)
            ->orderBy('code')
            ->get()
            ->mapWithKeys(fn ($account) => [$account->id => $account->code . ' - ' . $account->name])
            ->toArray();
    }

    public function autoMapAccounts(): void
    {
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Please select a company first.')
                ->send();
            return;
        }

        $accountCodes = [
            'accounts_receivable' => '11000300',
            'sales' => '40000100',
            'discount' => '41010400',
            'tax' => '160000',
            'cogs' => '500000',
            'inventory' => '120000',
            'sales_return' => '40000300',
            'advance_receivable' => '210200',
            'accounts_payable' => '210100',
            'purchases' => '51000100',
            'grni' => '210300',
            'purchase_return' => '51000200',
            'advance_payable' => '11000400',
        ];

        $accounts = Account::where('company_id', $companyId)
            ->whereIn('code', array_values($accountCodes))
            ->where('is_active', true)
            ->get()
            ->keyBy('code');

        $mappedCount = 0;
        $documentTypes = array_keys($this->getDocumentTypes());

        foreach ($documentTypes as $documentType) {
            $applicableMappings = AccountMapping::DOCUMENT_MAPPING_TYPES[$documentType] ?? [];

            foreach ($applicableMappings as $mappingType) {
                $accountCode = $accountCodes[$mappingType] ?? null;

                if ($accountCode && $accounts->has($accountCode)) {
                    $account = $accounts->get($accountCode);
                    $this->allMappings[$documentType][$mappingType]['account_id'] = (string) $account->id;
                    $mappedCount++;
                }
            }
        }

        if ($mappedCount > 0) {
            Notification::make()
                ->success()
                ->title('Success')
                ->body("Successfully auto-mapped {$mappedCount} accounts. Click 'Save All' to save.")
                ->send();
        } else {
            Notification::make()
                ->warning()
                ->title('No Mappings Found')
                ->body('No accounts with standard codes found to map.')
                ->send();
        }
    }

    public function saveAllMappings(): void
    {
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Please select a company first.')
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            $savedCount = 0;

            foreach ($this->allMappings as $documentType => $mappings) {
                foreach ($mappings as $mappingType => $data) {
                    if (empty($data['account_id'])) {
                        continue;
                    }

                    AccountMapping::updateOrCreate(
                        [
                            'company_id' => $companyId,
                            'document_type' => $documentType,
                            'mapping_type' => $mappingType,
                        ],
                        [
                            'account_id' => $data['account_id'],
                            'description' => $data['description'] ?? null,
                            'is_active' => true,
                        ]
                    );

                    $savedCount++;
                }
            }

            DB::commit();

            Notification::make()
                ->success()
                ->title('Success')
                ->body("Successfully saved {$savedCount} account mappings.")
                ->send();

            $this->originalMappings = $this->allMappings;

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Failed to save mappings: ' . $e->getMessage())
                ->send();
        }
    }
}
