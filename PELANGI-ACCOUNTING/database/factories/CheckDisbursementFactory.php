<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankAccount;
use App\Models\CheckDisbursement;
use App\Models\Company;
use App\Models\User;

class CheckDisbursementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CheckDisbursement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'check_number' => fake()->word(),
            'reference_no' => fake()->word(),
            'due_date' => fake()->date(),
            'description' => fake()->text(),
            'amount' => fake()->word(),
            'status' => fake()->randomElement(["draft","issued","cleared","bounced","cancelled","void"]),
            'bank_account_id' => BankAccount::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
