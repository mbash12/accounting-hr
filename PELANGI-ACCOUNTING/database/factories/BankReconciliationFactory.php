<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use App\Models\Company;
use App\Models\User;

class BankReconciliationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankReconciliation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'statement_date' => fake()->date(),
            'statement_balance' => fake()->word(),
            'book_balance' => fake()->word(),
            'reconciliation_date' => fake()->date(),
            'status' => fake()->randomElement(["pending","in_progress","completed","failed"]),
            'reconciled_at' => fake()->dateTime(),
            'difference' => fake()->word(),
            'bank_account_id' => BankAccount::factory(),
            'reconciled_by_user_id' => User::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
