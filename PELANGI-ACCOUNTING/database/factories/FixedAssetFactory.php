<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Department;
use App\Models\FixedAsset;
use App\Models\FixedAssetCategory;
use App\Models\JournalEntry;
use App\Models\User;

class FixedAssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FixedAsset::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'location' => fake()->regexify('[A-Za-z0-9]{200}'),
            'acquisition_date' => fake()->date(),
            'description' => fake()->text(),
            'acquisition_value' => fake()->randomFloat(2, 0, 9999999999999.99),
            'monthly_depreciation' => fake()->randomFloat(2, 0, 9999999999999.99),
            'depreciation_method' => fake()->randomElement(["straight_line","declining_balance","double_declining","sum_of_years","units_of_production"]),
            'accumulated_depreciation' => fake()->randomFloat(2, 0, 9999999999999.99),
            'useful_life' => fake()->numberBetween(-10000, 10000),
            'book_value' => fake()->randomFloat(2, 0, 9999999999999.99),
            'is_active' => fake()->boolean(),
            'category_id' => FixedAssetCategory::factory(),
            'department_id' => Department::factory(),
            'transaction_in_id' => JournalEntry::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
