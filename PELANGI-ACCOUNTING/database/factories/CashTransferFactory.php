<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankAccount;
use App\Models\CashTransfer;
use App\Models\Company;
use App\Models\User;

class CashTransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashTransfer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'amount' => fake()->word(),
            'status' => fake()->randomElement(["draft","approved","processed","cancelled"]),
            'from_account_id' => BankAccount::factory(),
            'to_account_id' => BankAccount::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
