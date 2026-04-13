<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\TransactionClassification;
use App\Models\User;

class TransactionClassificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionClassification::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'description' => fake()->text(),
            'classification_type' => fake()->randomElement(["operating","investing","financing","non_operating"]),
            'tax_impact' => fake()->randomElement(["taxable","exempt","zero_rated","out_of_scope"]),
            'reporting_category' => fake()->regexify('[A-Za-z0-9]{100}'),
            'is_active' => fake()->boolean(),
            'default_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
