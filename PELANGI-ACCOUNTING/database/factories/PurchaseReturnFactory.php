<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseReturn;
use App\Models\User;

class PurchaseReturnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseReturn::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["requested","approved","shipped","received","processed","rejected","cancelled"]),
            'supplier_id' => Contact::factory(),
            'purchase_invoice_id' => PurchaseInvoice::factory(),
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
