<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'code' => 'Box',
                'name' => 'Box',
                'description' => 'Box',
            ],
            [
                'code' => 'Cup',
                'name' => 'Cup',
                'description' => 'Cup',
            ],
            [
                'code' => 'Dzn',
                'name' => 'Dozen',
                'description' => 'Dozen',
            ],
            [
                'code' => 'Gr',
                'name' => 'Gram',
                'description' => 'Gram',
            ],
            [
                'code' => 'Gross',
                'name' => 'Gross',
                'description' => 'Gross',
            ],
            [
                'code' => 'Hour',
                'name' => 'Hour',
                'description' => 'Hour',
            ],
            [
                'code' => 'Kg',
                'name' => 'Kg',
                'description' => 'Kilogram',
            ],
            [
                'code' => 'Pack',
                'name' => 'Pack',
                'description' => 'Pack',
            ],
            [
                'code' => 'Pcs',
                'name' => 'Pcs',
                'description' => 'Pieces',
            ],
            [
                'code' => 'Score',
                'name' => 'Score',
                'description' => 'Score',
            ],
            [
                'code' => 'Ton',
                'name' => 'Ton',
                'description' => 'Ton',
            ],
        ];

        $this->command->info("Starting to seed " . count($units) . " units...");

        DB::beginTransaction();

        try {
            foreach ($units as $unitData) {
                Unit::create([
                    'code' => $unitData['code'],
                    'name' => $unitData['name'],
                    'description' => $unitData['description'],
                    'is_active' => true,
                    'company_id' => null, // Adjust based on your needs
                    'created_by_user_id' => 1, // Adjust based on your needs
                ]);
            }

            DB::commit();
            $this->command->info("✓ Successfully seeded " . count($units) . " units");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding units: " . $e->getMessage());
            throw $e;
        }
    }
}