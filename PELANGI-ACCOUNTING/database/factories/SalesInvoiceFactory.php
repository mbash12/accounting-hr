<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\User;

class SalesInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesInvoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'date' => fake()->date(),
            'due_date' => fake()->date(),
            'is_paid' => fake()->boolean(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'other_charges' => fake()->randomFloat(2, 0, 9999999999999.99),
            'discount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'subtotal' => fake()->randomFloat(2, 0, 9999999999999.99),
            'tax_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'paid_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'outstanding_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'is_advance_payment_invoice' => fake()->boolean(),
            'is_settlement_invoice' => fake()->boolean(),
            'status' => fake()->randomElement(["draft","sent","overdue","paid","partially_paid","written_off","cancelled"]),
            'customer_id' => Contact::factory(),
            'sales_order_id' => SalesOrder::factory(),
            'job_id' => Project::factory(),
            'other_charges_account_id' => Account::factory(),
            'discount_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
