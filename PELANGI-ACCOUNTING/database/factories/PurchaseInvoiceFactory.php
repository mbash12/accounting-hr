<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseInvoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'due_date' => fake()->date(),
            'is_paid' => fake()->boolean(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'other_charges' => fake()->word(),
            'discount' => fake()->word(),
            'advance_payment' => fake()->word(),
            'subtotal' => fake()->word(),
            'tax_amount' => fake()->word(),
            'total' => fake()->word(),
            'paid_amount' => fake()->word(),
            'outstanding_amount' => fake()->word(),
            'status' => fake()->randomElement(["draft","received","approved","paid","partially_paid","disputed","cancelled"]),
            'supplier_id' => Contact::factory(),
            'purchase_order_id' => PurchaseOrder::factory(),
            'job_id' => Project::factory(),
            'other_charges_account_id' => Account::factory(),
            'discount_account_id' => Account::factory(),
            'advance_payment_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
