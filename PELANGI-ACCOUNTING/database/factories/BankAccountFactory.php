<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\User;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'account_name' => fake()->regexify('[A-Za-z0-9]{200}'),
            'account_type' => fake()->randomElement(["checking","savings","credit_card","investment"]),
            'balance' => fake()->randomFloat(2, 0, 9999999999999.99),
            'is_active' => fake()->boolean(),
            'bank_id' => Bank::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
