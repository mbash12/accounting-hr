<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\FixedAssetCategory;
use App\Models\User;

class FixedAssetCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FixedAssetCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'depreciation_method' => fake()->randomElement(["straight_line","declining_balance","double_declining","sum_of_years","units_of_production"]),
            'useful_life' => fake()->numberBetween(-10000, 10000),
            'is_active' => fake()->boolean(),
            'sales_account_id' => Account::factory(),
            'asset_account_id' => Account::factory(),
            'accumulated_depreciation_account_id' => Account::factory(),
            'depreciation_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
