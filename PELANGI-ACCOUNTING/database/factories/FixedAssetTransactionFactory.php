<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\FixedAsset;
use App\Models\FixedAssetTransaction;
use App\Models\User;

class FixedAssetTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FixedAssetTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'journal_value' => fake()->word(),
            'asset_value' => fake()->word(),
            'difference' => fake()->word(),
            'transaction_type' => fake()->randomElement(["acquisition","depreciation","revaluation","disposal","impairment"]),
            'fixed_asset_id' => FixedAsset::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
