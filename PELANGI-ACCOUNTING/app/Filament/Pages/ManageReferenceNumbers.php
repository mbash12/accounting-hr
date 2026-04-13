<?php

namespace App\Filament\Pages;

use App\Models\DocumentNumbering;
use App\Models\Company;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\TextEntry;

use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use UnitEnum;

class ManageReferenceNumbers extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    public array $data = [];

    protected static ?string $navigationLabel = 'Nomor Referensi';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Nomor Referensi';

    protected string $view = 'filament.pages.manage-reference-numbers';

    public static function getNavigationLabel(): string
    {
        return __('Nomor Referensi');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public function getTitle(): string
    {
        return __('Nomor Referensi');
    }

    public function mount(): void
    {
        $selectedCompanyId = session('selected_company_id');

        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            $this->data = [];
        } else {
            $this->data = [
                'document_numberings' => $this->getDocumentNumberingsData()
            ];
        }
    }

    private function getDocumentNumberingsData(): array
    {
        $selectedCompanyId = session('selected_company_id');

        // Get records for the selected company (or all companies if specific company is selected)
        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            // Get company-specific records and global records
            $records = DocumentNumbering::where(function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId)
                      ->orWhereNull('company_id');
            })->get();

            // Key by document_type, prioritizing company-specific records (non-null company_id)
            // keyBy uses the last item for a key, so we sort such that company specific items are last
            $records = $records->sortBy(function ($record) {
                return $record->company_id === null ? 0 : 1;
            })->keyBy('document_type');
        } else {
            // When 'all' is selected, we should not show any records
            return [];
        }

        // Get all document types
        $documentTypes = $this->getDocumentTypes();

        $result = [];

        // Process each document type
        foreach ($documentTypes as $documentType => $label) {
            if (isset($records[$documentType])) {
                // Process existing record
                $record = $records[$documentType];
                $formatComponents = $record->format_components;

                // Migration for existing records without format_components
                if (empty($formatComponents)) {
                    $format = $record->format;
                    if ($format === '{prefix}{number}')
                        $formatComponents = ['prefix', 'number'];
                    elseif ($format === '{number}')
                        $formatComponents = ['number'];
                    elseif ($format === '{prefix}{year}{number}')
                        $formatComponents = ['prefix', 'year_full', 'number'];
                    elseif ($format === '{year}{prefix}{number}')
                        $formatComponents = ['year_full', 'prefix', 'number'];
                    elseif ($format === '{prefix}{month}{number}')
                        $formatComponents = ['prefix', 'month_short', 'number'];
                    elseif ($format === '{prefix}{year}{month}{number}')
                        $formatComponents = ['prefix', 'year_full', 'month_short', 'number'];
                    else
                        $formatComponents = ['prefix', 'number']; // Default fallback
                }

                $result[] = [
                    'id' => $record->id,
                    'document_type' => $record->document_type,
                    'document_type_display' => $this->getDocumentTypes()[$record->document_type] ?? $record->document_type,
                    'prefix' => $record->prefix,
                    'format' => $record->format,
                    'format_components' => $formatComponents,
                    'next_number' => $record->next_number,
                    'preview' => $this->generatePreview($record->prefix, $formatComponents, $record->next_number),
                    'is_automatic' => $record->is_active,
                    'is_active' => $record->is_active,
                    'reset_period' => $record->reset_period,
                ];
            } else {
                // Add default entry for missing document type
                $result[] = [
                    'document_type' => $documentType,
                    'document_type_display' => $this->getDocumentTypes()[$documentType] ?? $documentType,
                    'prefix' => $this->getDefaultPrefix($documentType),
                    'format' => '{prefix}{number}',
                    'format_components' => ['prefix', 'number'],
                    'next_number' => 0,
                    'preview' => $this->generatePreview($this->getDefaultPrefix($documentType), ['prefix', 'number'], 0),
                    'is_automatic' => true,
                    'is_active' => true,
                    'reset_period' => 'never',
                ];
            }
        }

        // Sort by document type label name
        usort($result, function ($a, $b) use ($documentTypes) {
            return strcmp($documentTypes[$a['document_type']] ?? $a['document_type'],
                         $documentTypes[$b['document_type']] ?? $b['document_type']);
        });

        return $result;
    }

    private function getDefaultPrefix(string $documentType): string
    {
        $prefixMap = [
            'sales_invoice' => 'INV',
            'purchase_invoice' => 'SUP',
            'cash_receipt' => 'CR',
            'cash_disbursement' => 'CD',
            'journal_entry' => 'JE',
            'sales_order' => 'SO',
            'purchase_order' => 'PO',
            'product' => 'PRD',
            'product_group' => 'PRG',
            'project' => 'PR',
            'fixed_asset' => 'FA',
            'unit_measurement' => 'UM',
            'bank_account' => 'BA',
            'warehouse' => 'WH',
            'department' => 'DPT',
            'tax' => 'TAX',
            'expedition' => 'EXP',
            'contact' => 'CT',
            'bank' => 'BK',
            'business_type' => 'BT',
            'payment_term' => 'PT',
            'advance_disbursement' => 'ADV',
            'advance_receipt' => 'AR',
            'cash_transfer' => 'TRF',
            'check_disbursement' => 'CHK',
            'delivery_document' => 'DO',
            'fixed_asset_transaction' => 'FAT',
            'goods_receipt' => 'GR',
            'inventory_adjustment' => 'IA',
            'overpayment_receipt' => 'OR',
            'overpayment_refund' => 'RF',
            'payable_payment' => 'PP',
            'purchase_return' => 'PRN',
            'receivable_payment' => 'RP',
            'sales_return' => 'SRN',
            'stock_opname' => 'SO',
            'warehouse_transfer' => 'WT',
        ];

        return $prefixMap[$documentType] ?? strtoupper(substr($documentType, 0, 3));
    }

    public function getDocumentTypes(): array
    {
        return [
            'advance_disbursement' => 'Pengeluaran Uang Muka',
            'advance_receipt' => 'Penerimaan Uang Muka',
            'cash_disbursement' => 'Pengeluaran Kas',
            'cash_receipt' => 'Penerimaan Kas',
            'cash_transfer' => 'Transfer Kas',
            'check_disbursement' => 'Pengeluaran Cek',
            'delivery_document' => 'Dokumen Pengiriman',
            'fixed_asset_transaction' => 'Transaksi Aset Tetap',
            'goods_receipt' => 'Penerimaan Barang',
            'inventory_adjustment' => 'Penyesuaian Persediaan',
            'journal_entry' => 'Jurnal Umum',
            'overpayment_receipt' => 'Penerimaan Kelebihan Bayar',
            'overpayment_refund' => 'Pengembalian Kelebihan Bayar',
            'payable_payment' => 'Pembayaran Hutang',
            'purchase_invoice' => 'Faktur Pembelian',
            'purchase_order' => 'Pesanan Pembelian',
            'purchase_return' => 'Retur Pembelian',
            'receivable_payment' => 'Pembayaran Piutang',
            'sales_invoice' => 'Faktur Penjualan',
            'sales_order' => 'Pesanan Penjualan',
            'sales_return' => 'Retur Penjualan',
            'stock_opname' => 'Stok Opname',
            'warehouse_transfer' => 'Transfer Gudang',
            'product' => 'Produk',
            'product_group' => 'Grup Produk',
            'fixed_asset' => 'Aset Tetap',
            'unit_measurement' => 'Satuan',
            'bank_account' => 'Rekening Bank',
            'warehouse' => 'Gudang',
            'department' => 'Departemen',
            'tax' => 'Pajak',
            'expedition' => 'Ekspedisi',
            'contact' => 'Kontak',
            'bank' => 'Bank',
            'business_type' => 'Jenis Usaha',
            'payment_term' => 'Termin Pembayaran',
            'project' => 'Proyek',
        ];
    }

    public function getFormatComponentOptions(): array
    {
        return [
            'prefix' => '{CODE}',
            'year_full' => '{YYYY}',
            'year_short' => '{YY}',
            'month_full' => '{MMMM}',
            'month_medium' => '{MMM}',
            'month_short' => '{MM}',
            'month_numeric' => '{M}',
            'number' => '{NUMBER}',
        ];
    }

    public function getResetPeriodOptions(): array
    {
        return [
            'never' => 'Tidak Pernah',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulanan',
            'yearly' => 'Tahunan',
        ];
    }

    private function generatePreview(string $prefix, array $formatComponents, int $nextNumber): string
    {
        $previewParts = [];
        $nextNumber++; // Add 1 to show what the NEXT generated number will be

        foreach ($formatComponents as $component) {
            switch ($component) {
                case 'prefix':
                    $previewParts[] = $prefix;
                    break;
                case 'year_full':
                    $previewParts[] = date('Y');
                    break;
                case 'year_short':
                    $previewParts[] = date('y');
                    break;
                case 'month_full':
                    $previewParts[] = date('F');
                    break;
                case 'month_medium':
                    $previewParts[] = date('M');
                    break;
                case 'month_short':
                    $previewParts[] = date('m');
                    break;
                case 'month_numeric':
                    $previewParts[] = date('n');
                    break;
                case 'number':
                    $previewParts[] = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                    break;
            }
        }
        return implode('', $previewParts);
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('document_numberings')
                ->label('Pengaturan Nomor Referensi')
                ->table([
                    TableColumn::make('Nama Modul'),
                    TableColumn::make('Kode')->width('10%'),
                    TableColumn::make('Format')->width('40%'),
                    TableColumn::make('Nomor')->width('10%'),
                    TableColumn::make('Contoh'),
                    TableColumn::make('Reset'),
                    TableColumn::make('Otomatis')->width('1%'),
                ])
                ->schema([
                    \Filament\Forms\Components\Hidden::make('id'),
                    \Filament\Forms\Components\Hidden::make('document_type'),

                    TextEntry::make('document_type_display')
                        ->label('Nama Modul')
                        ->formatStateUsing(fn($state, $record): string => $this->getDocumentTypes()[$record['document_type'] ?? $state] ?? ($record['document_type'] ?? $state)),

                    TextInput::make('prefix')
                        ->required()
                        ->maxLength(20)
                        ->placeholder('TR')
                        ->live(),

                    Select::make('format_components')
                        ->label('Format')
                        ->options($this->getFormatComponentOptions())
                        ->multiple()
                        ->required()
                        ->default(['prefix', 'number'])
                        ->live(),

                    TextInput::make('next_number')
                        ->label('Number')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0)
                        ->live()
                        ->readOnly(),

                    \Filament\Forms\Components\Placeholder::make('preview')
                        ->label('Preview')
                        ->content(function ($get) {
                            $components = $get('format_components') ?? [];
                            $prefix = $get('prefix') ?? 'TR';
                            // Add 1 to next_number to show what the NEXT generated number will be
                            $nextNumber = ($get('next_number') ?? 0) + 1;

                            $previewParts = [];
                            foreach ($components as $component) {
                                switch ($component) {
                                    case 'prefix':
                                        $previewParts[] = $prefix;
                                        break;
                                    case 'year_full':
                                        $previewParts[] = date('Y');
                                        break;
                                    case 'year_short':
                                        $previewParts[] = date('y');
                                        break;
                                    case 'month_full':
                                        $previewParts[] = date('F');
                                        break;
                                    case 'month_medium':
                                        $previewParts[] = date('M');
                                        break;
                                    case 'month_short':
                                        $previewParts[] = date('m');
                                        break;
                                    case 'month_numeric':
                                        $previewParts[] = date('n');
                                        break;
                                    case 'number':
                                        $previewParts[] = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                                        break;
                                }
                            }
                            return implode('', $previewParts);
                        }),

                    Select::make('reset_period')
                        ->options($this->getResetPeriodOptions())
                        ->required()
                        ->default('never')
                        ->disablePlaceholderSelection(),

                    Toggle::make('is_automatic')
                        ->default(true),

                ])
                ->compact()
                ->deletable(false)
                ->addable(false)
                ->collapsible()
                ->defaultItems(0)
                ->reorderable(false)
                ->itemLabel(
                    fn(array $state): ?string =>
                    isset($state['document_type']) ? ($this->getDocumentTypes()[$state['document_type']] ?? $state['document_type']) : null
                )
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $selectedCompanyId = session('selected_company_id');

        if (!$selectedCompanyId || $selectedCompanyId === 'all') {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Please select a company first before saving reference number configurations.')
                ->send();
            return;
        }

        DB::beginTransaction();

        try {
            foreach ($data['document_numberings'] as $item) {
                // Check if document_type exists, otherwise skip this item
                if (!isset($item['document_type'])) {
                    continue; // Skip items without document_type
                }

                $updateData = [
                    'prefix' => $item['prefix'],
                    // 'format' => $item['format'], // Format is generated from components in model
                    'format_components' => $item['format_components'],
                    'next_number' => $item['next_number'],
                    'is_active' => $item['is_automatic'],
                    'reset_period' => $item['reset_period'],
                ];

                if (isset($item['id']) && $item['id']) {
                    // Update existing record
                    $documentNumbering = DocumentNumbering::find($item['id']);
                    if ($documentNumbering) {
                        // Only update if the record belongs to the current company or is global
                        // Use loose comparison for company_id to handle string/int differences
                        if ($documentNumbering->company_id == $selectedCompanyId || $documentNumbering->company_id === null) {
                            // If it's a global record, create a company-specific copy instead
                            if ($documentNumbering->company_id === null) {
                                DocumentNumbering::create(array_merge($updateData, [
                                    'document_type' => $item['document_type'],
                                    'company_id' => $selectedCompanyId,
                                    'created_by_user_id' => auth()->id(),
                                ]));
                            } else {
                                $documentNumbering->update($updateData);
                            }
                        }
                    }
                } else {
                    // Create new record
                    DocumentNumbering::create(array_merge($updateData, [
                        'document_type' => $item['document_type'],
                        'company_id' => $selectedCompanyId,
                        'created_by_user_id' => auth()->id(),
                    ]));
                }
            }

            DB::commit();

            Notification::make()
                ->success()
                ->title('Berhasil')
                ->body('Pengaturan nomor referensi telah disimpan.')
                ->send();

            // Refresh the data
            $this->mount();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Terjadi kesalahan saat menyimpan pengaturan: ' . $e->getMessage())
                ->send();
        }
    }
}
