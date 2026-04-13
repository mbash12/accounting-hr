<?php

namespace Database\Seeders;

use App\Models\FixedAssetCategoryTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixedAssetCategoryTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Gedung',
                'depreciation_method' => 'straight_line',
                'useful_life' => 20,
                'is_active' => true,
                'asset_account_code' => '121000101',
                'accumulated_depreciation_account_code' => '122000100',
                'depreciation_account_code' => '710000002',
                'sales_account_code' => '400000001',
            ],
            [
                'name' => 'Harta Lainnya',
                'depreciation_method' => 'straight_line',
                'useful_life' => 4,
                'is_active' => true,
                'asset_account_code' => '121000104',
                'accumulated_depreciation_account_code' => '122000103',
                'depreciation_account_code' => '710000005',
                'sales_account_code' => '400000001',
            ],
            [
                'name' => 'Kendaraan',
                'depreciation_method' => 'straight_line',
                'useful_life' => 8,
                'is_active' => true,
                'asset_account_code' => '121000103',
                'accumulated_depreciation_account_code' => '122000102',
                'depreciation_account_code' => '710000004',
                'sales_account_code' => '400000001',
            ],
            [
                'name' => 'Mesin & Peralatan',
                'depreciation_method' => 'straight_line',
                'useful_life' => 4,
                'is_active' => true,
                'asset_account_code' => '121000102',
                'accumulated_depreciation_account_code' => '122000101',
                'depreciation_account_code' => '710000003',
                'sales_account_code' => '400000001',
            ],
            [
                'name' => 'Tanah',
                'depreciation_method' => 'straight_line',
                'useful_life' => 0,
                'is_active' => true,
                'asset_account_code' => '121000100',
                'accumulated_depreciation_account_code' => null,
                'depreciation_account_code' => null,
                'sales_account_code' => '400000001',
            ],
        ];

        $this->command->info("Starting to seed " . count($categories) . " fixed asset category templates...");

        DB::beginTransaction();

        try {
            $templateName = 'Standard Indonesian Fixed Asset Categories';
            
            // Clear existing templates with the same name
            FixedAssetCategoryTemplate::where('template_name', $templateName)->delete();
            
            foreach ($categories as $categoryData) {
                FixedAssetCategoryTemplate::create([
                    'name' => $categoryData['name'],
                    'depreciation_method' => $categoryData['depreciation_method'],
                    'useful_life' => $categoryData['useful_life'],
                    'is_active' => $categoryData['is_active'],
                    'asset_account_code' => $categoryData['asset_account_code'],
                    'accumulated_depreciation_account_code' => $categoryData['accumulated_depreciation_account_code'],
                    'depreciation_account_code' => $categoryData['depreciation_account_code'],
                    'sales_account_code' => $categoryData['sales_account_code'],
                    'template_name' => $templateName,
                    'notes' => 'Standard Indonesian Fixed Asset Category template',
                ]);
            }

            DB::commit();
            $this->command->info("✓ Successfully seeded " . count($categories) . " fixed asset category templates as '{$templateName}'");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding fixed asset category templates: " . $e->getMessage());
            throw $e;
        }
    }
}