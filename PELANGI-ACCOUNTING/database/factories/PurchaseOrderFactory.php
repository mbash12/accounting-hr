<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseOrder::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'purchase_order_no' => fake()->word(),
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'other_charges' => fake()->word(),
            'discount' => fake()->word(),
            'subtotal' => fake()->word(),
            'tax_amount' => fake()->word(),
            'total' => fake()->word(),
            'status' => fake()->randomElement(["draft","sent","confirmed","partially_received","completed","cancelled"]),
            'supplier_id' => Contact::factory(),
            'job_id' => Project::factory(),
            'department_id' => Department::factory(),
            'other_charges_account_id' => Account::factory(),
            'discount_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
