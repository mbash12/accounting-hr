<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\ReceivablePayment;
use App\Models\User;

class ReceivablePaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReceivablePayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'payment_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'payment_date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'total_payment' => fake()->randomFloat(2, 0, 9999999999999.99),
            'payment_method' => fake()->randomElement(["cash","bank_transfer","check","credit_card","debit_card","online_payment","other"]),
            'status' => fake()->randomElement(["pending","completed","failed","cancelled"]),
            'customer_id' => Contact::factory(),
            'bank_account_id' => BankAccount::factory(),
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
