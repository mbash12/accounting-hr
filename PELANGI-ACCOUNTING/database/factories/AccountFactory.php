<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\User;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'name' => fake()->name(),
            'account_type' => fake()->randomElement(["current_asset","fixed_asset","current_liability","long_term_liability","equity","revenue","expense","cost_of_goods_sold"]),
            'classification_type' => fake()->randomElement(["asset","liability","equity","revenue","expense"]),
            'description' => fake()->text(),
            'is_header' => fake()->boolean(),
            'is_cash_bank' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'level' => fake()->numberBetween(1, 5),
            'opening_balance' => fake()->randomFloat(2, 0, 9999999999999.99),
            'current_balance' => fake()->randomFloat(2, 0, 9999999999999.99),
            'parent_id' => null,
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
