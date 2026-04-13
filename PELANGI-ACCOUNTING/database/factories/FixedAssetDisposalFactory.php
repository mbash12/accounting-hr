<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\FixedAsset;
use App\Models\FixedAssetDisposal;
use App\Models\User;

class FixedAssetDisposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FixedAssetDisposal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'reference_no' => fake()->word(),
            'date' => fake()->date(),
            'description' => fake()->text(),
            'disposal_value' => fake()->word(),
            'fixed_asset_id' => FixedAsset::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
