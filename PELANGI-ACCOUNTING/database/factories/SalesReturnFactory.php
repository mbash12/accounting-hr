<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\DeliveryDocument;
use App\Models\Project;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use App\Models\User;

class SalesReturnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesReturn::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["requested","approved","received","processed","rejected","cancelled"]),
            'customer_id' => Contact::factory(),
            'sales_invoice_id' => SalesInvoice::factory(),
            'delivery_document_id' => DeliveryDocument::factory(),
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
