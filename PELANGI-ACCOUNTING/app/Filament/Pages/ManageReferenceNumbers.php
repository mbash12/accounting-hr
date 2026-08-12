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

    protected static ?string $navigationLabel = 'Reference Numbers';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Reference Numbers';

    protected string $view = 'filament.pages.manage-reference-numbers';

    public static function getNavigationLabel(): string
    {
        return __('Reference Numbers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public function getTitle(): string
    {
        return __('Reference Numbers');
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

        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $records = DocumentNumbering::where(function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId)
                      ->orWhereNull('company_id');
            })->get();

            $records = $records->sortBy(function ($record) {
                return $record->company_id === null ? 0 : 1;
            })->keyBy('document_type');
        } else {
            return [];
        }

        $documentTypes = $this->getDocumentTypes();

        $result = [];

        foreach ($documentTypes as $documentType => $label) {
            if (isset($records[$documentType])) {
                $record = $records[$documentType];
                $formatComponents = $record->format_components;

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
                        $formatComponents = ['prefix', 'number'];
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
            'advance_disbursement' => 'Advance Disbursement',
            'advance_receipt' => 'Advance Receipt',
            'cash_disbursement' => 'Cash Disbursement',
            'cash_receipt' => 'Cash Receipt',
            'cash_transfer' => 'Cash Transfer',
            'check_disbursement' => 'Check Disbursement',
            'delivery_document' => 'Delivery Document',
            'fixed_asset_transaction' => 'Fixed Asset Transaction',
            'goods_receipt' => 'Goods Receipt',
            'inventory_adjustment' => 'Inventory Adjustment',
            'journal_entry' => 'Journal Entry',
            'overpayment_receipt' => 'Overpayment Receipt',
            'overpayment_refund' => 'Overpayment Refund',
            'payable_payment' => 'Payable Payment',
            'purchase_invoice' => 'Purchase Invoice',
            'purchase_order' => 'Purchase Order',
            'purchase_return' => 'Purchase Return',
            'receivable_payment' => 'Receivable Payment',
            'sales_invoice' => 'Sales Invoice',
            'sales_order' => 'Sales Order',
            'sales_return' => 'Sales Return',
            'stock_opname' => 'Stock Opname',
            'warehouse_transfer' => 'Warehouse Transfer',
            'product' => 'Product',
            'product_group' => 'Product Group',
            'fixed_asset' => 'Fixed Asset',
            'unit_measurement' => 'Unit of Measurement',
            'bank_account' => 'Bank Account',
            'warehouse' => 'Warehouse',
            'department' => 'Department',
            'tax' => 'Tax',
            'expedition' => 'Expedition',
            'contact' => 'Contact',
            'bank' => 'Bank',
            'business_type' => 'Business Type',
            'payment_term' => 'Payment Term',
            'project' => 'Project',
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
            'never' => 'Never',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
        ];
    }

    private function generatePreview(string $prefix, array $formatComponents, int $nextNumber): string
    {
        $previewParts = [];
        $nextNumber++;

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
                ->label('Reference Number Settings')
                ->table([
                    TableColumn::make('Module Name'),
                    TableColumn::make('Code')->width('10%'),
                    TableColumn::make('Format')->width('40%'),
                    TableColumn::make('Number')->width('10%'),
                    TableColumn::make('Example'),
                    TableColumn::make('Reset'),
                    TableColumn::make('Automatic')->width('1%'),
                ])
                ->schema([
                    \Filament\Forms\Components\Hidden::make('id'),
                    \Filament\Forms\Components\Hidden::make('document_type'),

                    TextEntry::make('document_type_display')
                        ->label('Module Name')
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
                if (!isset($item['document_type'])) {
                    continue;
                }

                $updateData = [
                    'prefix' => $item['prefix'],
                    'format_components' => $item['format_components'],
                    'next_number' => $item['next_number'],
                    'is_active' => $item['is_automatic'],
                    'reset_period' => $item['reset_period'],
                ];

                if (isset($item['id']) && $item['id']) {
                    $documentNumbering = DocumentNumbering::find($item['id']);
                    if ($documentNumbering) {
                        if ($documentNumbering->company_id == $selectedCompanyId || $documentNumbering->company_id === null) {
                            if ($documentNumbering->company_id === null) {
                                DocumentNumbering::create(array_merge($updateData, [
                                    'document_type' => $item['document_type'],
                                    'company_id' => $selectedCompanyId,
                                    'created_by_user_id' => auth()->id() ?? 1,
                                    ]));
                            } else {
                                $documentNumbering->update($updateData);
                            }
                        }
                    }
                } else {
                    DocumentNumbering::create(array_merge($updateData, [
                        'document_type' => $item['document_type'],
                        'company_id' => $selectedCompanyId,
                        'created_by_user_id' => auth()->id() ?? 1,
                    ]));
                }
            }

            DB::commit();

            Notification::make()
                ->success()
                ->title('Success')
                ->body('Reference number settings have been saved.')
                ->send();

            $this->mount();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('An error occurred while saving settings: ' . $e->getMessage())
                ->send();
        }
    }
}
