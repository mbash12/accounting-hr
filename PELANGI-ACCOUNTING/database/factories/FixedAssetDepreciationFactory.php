<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FixedAsset;
use App\Models\FixedAssetDepreciation;

class FixedAssetDepreciationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FixedAssetDepreciation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'year_number' => fake()->numberBetween(-10000, 10000),
            'period_start' => fake()->date(),
            'period_end' => fake()->date(),
            'months_count' => fake()->numberBetween(-10000, 10000),
            'beginning_book_value' => fake()->word(),
            'percentage' => fake()->randomFloat(2, 0, 999.99),
            'yearly_depreciation' => fake()->word(),
            'monthly_depreciation' => fake()->word(),
            'ending_book_value' => fake()->word(),
            'fixed_asset_id' => FixedAsset::factory(),
        ];
    }
}
