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
    protected static ?string $navigationLabel = 'Pemetaan Akun';

    protected static string|UnitEnum|null $navigationGroup = 'Buku Besar';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Pemetaan Akun & Jurnal';

    public static function getNavigationLabel(): string
    {
        return __('Pemetaan Akun');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Buku Besar');
    }

    public function getTitle(): string
    {
        return __('Pemetaan Akun & Jurnal');
    }

    protected string $view = 'filament.pages.manage-account-mappings';

    public $selectedDocumentType = null;

    public array $allMappings = []; // Store all mappings for all document types
    
    public array $originalMappings = []; // Store original state to detect changes

    protected function getHeaderActions(): array
    {
        return [
            Action::make('auto_map')
                ->label('Pemetaan Otomatis')
                ->icon('heroicon-o-sparkles')
                ->color('gray')
                ->action('autoMapAccounts')
                ->requiresConfirmation()
                ->modalHeading('Pemetaan Otomatis Akun')
                ->modalDescription('Sistem akan mencari dan memetakan akun secara otomatis berdasarkan kode akun standar. Pemetaan yang sudah ada akan diganti.')
                ->modalSubmitActionLabel('Ya, Petakan Otomatis')
                ->disabled(fn () => !session('selected_company_id') || session('selected_company_id') === 'all'),
            Action::make('save')
                ->label('Simpan Semua')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('saveAllMappings')
                ->disabled(fn () => !session('selected_company_id') || session('selected_company_id') === 'all'),
        ];
    }

    public function mount(): void
    {
        // Default to the first document type if none selected
        if (empty($this->selectedDocumentType)) {
            $documentTypes = array_keys($this->getDocumentTypes());
            $this->selectedDocumentType = $documentTypes[0] ?? null;
        }

        // If selected type is not in allowed types, reset to first allowed
        $allowedTypes = array_keys($this->getDocumentTypes());
        if (!in_array($this->selectedDocumentType, $allowedTypes)) {
            $this->selectedDocumentType = $allowedTypes[0] ?? null;
        }

        $this->loadAllMappings();
        $this->originalMappings = $this->allMappings; // Store original state
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

        // Load all mappings for all document types at once
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

        // Only show sales and purchase related types for now
        $allowedTypes = [
            'sales_order',
            'delivery_document',
            'sales_invoice',
            'sales_return',
            'purchase_order',
            'goods_receipt',
            'purchase_invoice',
            'purchase_return',
            'receivable_payment',
            'payable_payment',
            'payroll',
            ];

            $types = array_intersect_key($allTypes, array_flip($allowedTypes));

            // Translate to Bahasa Indonesia
            $translations = [
            'sales_order' => 'Pesanan Penjualan',
            'delivery_document' => 'Pengiriman Penjualan',
            'sales_invoice' => 'Faktur Penjualan',
            'sales_return' => 'Retur Penjualan',
            'purchase_order' => 'Pesanan Pembelian',
            'goods_receipt' => 'Penerimaan Barang',
            'purchase_invoice' => 'Faktur Pembelian',
            'purchase_return' => 'Retur Pembelian',
            'receivable_payment' => 'Pembayaran Piutang',
            'payable_payment' => 'Pembayaran Utang',
            'payroll' => 'Gaji & Upah (Payroll)',
            ];

        
        return array_map(fn($key) => $translations[$key] ?? $types[$key], array_combine(array_keys($types), array_keys($types)));
    }

    public function getMappingTypes(): array
    {
        if (!$this->selectedDocumentType) {
            return [];
        }

        $mappingTypes = AccountMapping::DOCUMENT_MAPPING_TYPES[$this->selectedDocumentType] ?? [];
        
        // Translate to Bahasa Indonesia
        $translations = [
            'sales' => 'Pendapatan Penjualan',
            'accounts_receivable' => 'Piutang Usaha',
            'discount' => 'Diskon',
            'tax' => 'Pajak',
            'other_charges' => 'Biaya Lain',
            'cogs' => 'Harga Pokok Penjualan',
            'inventory' => 'Persediaan',
            'accounts_payable' => 'Hutang Usaha',
            'purchases' => 'Pembelian/Beban',
            'sales_return' => 'Retur Penjualan',
            'purchase_return' => 'Retur Pembelian',
            'advance_receivable' => 'Uang Muka Penjualan',
            'advance_payable' => 'Uang Muka Pembelian',
            'grni' => 'Barang Diterima Belum Difaktur',
            'cash' => 'Kas',
            'bank' => 'Bank',
            'expense' => 'Beban',
            'gain' => 'Pendapatan Lain',
            'loss' => 'Beban Lain',
            'write_off' => 'Penghapusan (Write Off)',
            'salary_expense' => 'Beban Gaji',
            'bpjs_expense' => 'Beban BPJS (Perusahaan)',
            'salary_payable' => 'Utang Gaji (Bersih)',
            'pph21_payable' => 'Utang PPh21',
            'bpjs_payable' => 'Utang BPJS (Total)',
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
                ->body('Silakan pilih perusahaan terlebih dahulu')
                ->send();
            return;
        }

        // Standard account code mappings
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

        // Load accounts by code
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
                ->title('Berhasil')
                ->body("Berhasil memetakan {$mappedCount} akun secara otomatis. Klik 'Simpan Semua' untuk menyimpan.")
                ->send();
        } else {
            Notification::make()
                ->warning()
                ->title('Tidak Ada Pemetaan')
                ->body('Tidak ditemukan akun dengan kode standar untuk dipetakan.')
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
                ->body('Silakan pilih perusahaan terlebih dahulu')
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            $savedCount = 0;
            
            // Save all mappings for all document types
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
                ->title('Berhasil')
                ->body("Berhasil menyimpan {$savedCount} pemetaan akun")
                ->send();
            
            // Update original state after successful save
            $this->originalMappings = $this->allMappings;

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Gagal menyimpan pemetaan: ' . $e->getMessage())
                ->send();
        }
    }
}
