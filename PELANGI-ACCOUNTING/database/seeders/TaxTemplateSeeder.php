<?php

namespace Database\Seeders;

use App\Models\TaxTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            [
                'code' => '.',
                'name' => 'No Tax',
                'tax_percentage' => 0,
                'tax_type' => 'vat',
                'purchase_account_code' => '116200006',
                'sales_account_code' => '213000006',
            ],
            [
                'code' => 'PPN',
                'name' => 'Pajak Pertambahan Nilai',
                'tax_percentage' => 11,
                'tax_type' => 'vat',
                'purchase_account_code' => '116200010',
                'sales_account_code' => '213000010',
            ],
            [
                'code' => 'PPh 23-4',
                'name' => 'PPh Pasal 23 Non NPWP',
                'tax_percentage' => 4,
                'tax_type' => 'withholding_tax',
                'purchase_account_code' => '213000009',
                'sales_account_code' => '116200009',
            ],
            [
                'code' => 'PPh 23-2',
                'name' => 'PPh Pasal 23 NPWP',
                'tax_percentage' => 2,
                'tax_type' => 'withholding_tax',
                'purchase_account_code' => '213000007',
                'sales_account_code' => '116200007',
            ],
            [
                'code' => 'PPh 4.2',
                'name' => 'PPh Pasal 4 Ayat 2',
                'tax_percentage' => 1,
                'tax_type' => 'withholding_tax',
                'purchase_account_code' => '213000008',
                'sales_account_code' => '116200008',
            ],
            [
                'code' => 'ppnbm20',
                'name' => 'PPnBM 20%',
                'tax_percentage' => 20,
                'tax_type' => 'excise_tax',
                'purchase_account_code' => '116200011',
                'sales_account_code' => '213000011',
            ],
        ];

        $this->command->info("Starting to seed " . count($taxes) . " tax templates...");

        DB::beginTransaction();

        try {
            $templateName = 'Standard Indonesian Taxes';
            
            // Clear existing templates with the same name
            TaxTemplate::where('template_name', $templateName)->delete();
            
            foreach ($taxes as $taxData) {
                TaxTemplate::create([
                    'name' => $taxData['name'],
                    'code' => $taxData['code'],
                    'tax_percentage' => $taxData['tax_percentage'],
                    'tax_type' => $taxData['tax_type'],
                    'is_purchase_tax' => true,
                    'is_sales_tax' => true,
                    'effective_date' => now()->format('Y-m-d'),
                    'expiry_date' => null,
                    'compound_tax' => false,
                    'is_active' => true,
                    'purchase_account_code' => $taxData['purchase_account_code'],
                    'sales_account_code' => $taxData['sales_account_code'],
                    'template_name' => $templateName,
                    'notes' => 'Standard Indonesian Tax template',
                ]);
            }

            DB::commit();
            $this->command->info("✓ Successfully seeded " . count($taxes) . " tax templates as '{$templateName}'");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding tax templates: " . $e->getMessage());
            throw $e;
        }
    }
}