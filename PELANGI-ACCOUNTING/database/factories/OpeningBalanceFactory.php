<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\OpeningBalance;
use App\Models\User;

class OpeningBalanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OpeningBalance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'balance_type' => fake()->randomElement(["debit","credit"]),
            'amount' => fake()->word(),
            'date' => fake()->date(),
            'description' => fake()->text(),
            'account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
