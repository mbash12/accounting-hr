<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankAccount;
use App\Models\CashDisbursement;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;

class CashDisbursementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashDisbursement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'total' => fake()->word(),
            'status' => fake()->randomElement(["draft","approved","processed","cancelled"]),
            'recipient_id' => Contact::factory(),
            'from_account_id' => BankAccount::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
