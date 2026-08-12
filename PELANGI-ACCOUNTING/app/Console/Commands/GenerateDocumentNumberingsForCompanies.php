<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\DocumentNumbering;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateDocumentNumberingsForCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-document-numberings-for-companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate default document numbering configurations for companies that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating document numbering configurations for companies...');

        $companies = Company::all();
        $documentTypes = $this->getDocumentTypesWithDefaults();

        foreach ($companies as $company) {
            $this->line('Processing company: ' . $company->name);

            foreach ($documentTypes as $documentType => $config) {
                $existing = DocumentNumbering::where('document_type', $documentType)
                    ->where('company_id', $company->id)
                    ->first();

                if (!$existing) {
                    DocumentNumbering::create([
                        'document_type' => $documentType,
                        'prefix' => $config['prefix'],
                        'format' => $config['format'],
                        'format_components' => $config['format_components'],
                        'next_number' => 0,
                        'reset_period' => 'never',
                        'is_active' => true,
                        'company_id' => $company->id,
                        'created_by_user_id' => 1, // Default to admin user
                    ]);

                    $this->line("  - Created {$documentType} with prefix {$config['prefix']}");
                } else {
                    $this->line("  - {$documentType} already exists, skipping");
                }
            }

            $this->newLine();
        }

        $this->info('Document numbering configurations generated successfully!');
    }

    /**
     * Get document types with their default configurations
     */
    private function getDocumentTypesWithDefaults(): array
    {
        return [
            'sales_invoice' => [
                'prefix' => 'INV',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_invoice' => [
                'prefix' => 'SUP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_receipt' => [
                'prefix' => 'CR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_disbursement' => [
                'prefix' => 'CD',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'journal_entry' => [
                'prefix' => 'JE',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'sales_order' => [
                'prefix' => 'SO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_order' => [
                'prefix' => 'PO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'product' => [
                'prefix' => 'PRD',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'product_group' => [
                'prefix' => 'PRG',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'fixed_asset' => [
                'prefix' => 'FA',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'unit_measurement' => [
                'prefix' => 'UM',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'bank_account' => [
                'prefix' => 'BA',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'warehouse' => [
                'prefix' => 'WH',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'department' => [
                'prefix' => 'DPT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'tax' => [
                'prefix' => 'TAX',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'expedition' => [
                'prefix' => 'EXP',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'contact' => [
                'prefix' => 'CT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'supplier' => [
                'prefix' => 'SP-',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'customer' => [
                'prefix' => 'CP-',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'bank' => [
                'prefix' => 'BK',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'business_type' => [
                'prefix' => 'BT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'payment_term' => [
                'prefix' => 'PT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'advance_disbursement' => [
                'prefix' => 'ADV',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'advance_receipt' => [
                'prefix' => 'AR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_transfer' => [
                'prefix' => 'TRF',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'check_disbursement' => [
                'prefix' => 'CHK',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'delivery_document' => [
                'prefix' => 'DO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'fixed_asset_transaction' => [
                'prefix' => 'FAT',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'goods_receipt' => [
                'prefix' => 'GR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'inventory_adjustment' => [
                'prefix' => 'IA',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'overpayment_receipt' => [
                'prefix' => 'OR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'overpayment_refund' => [
                'prefix' => 'RF',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'payable_payment' => [
                'prefix' => 'PP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_return' => [
                'prefix' => 'PRN',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'receivable_payment' => [
                'prefix' => 'RP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'sales_return' => [
                'prefix' => 'SRN',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'stock_opname' => [
                'prefix' => 'SO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'warehouse_transfer' => [
                'prefix' => 'WT',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
        ];
    }
}
