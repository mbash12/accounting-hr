<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\AdvancePayment;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\SalesOrder;
use App\Models\User;

class SalesOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesOrder::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'order_type' => fake()->randomElement(["standard","cash","credit","consignment","service"]),
            'date' => fake()->date(),
            'is_closed' => fake()->boolean(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'other_charges' => fake()->randomFloat(2, 0, 9999999999999.99),
            'discount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999.99),
            'tax_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'status' => fake()->randomElement(["draft","confirmed","partially_delivered","completed","cancelled"]),
            'job_id' => Project::factory(),
            'customer_id' => Contact::factory(),
            'advance_payment_id' => AdvancePayment::factory(),
            'other_charges_account_id' => Account::factory(),
            'discount_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
