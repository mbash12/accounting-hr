<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\GoodsReceipt;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\User;

class GoodsReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoodsReceipt::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'is_closed' => fake()->boolean(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["pending","received","inspected","approved","rejected","cancelled"]),
            'supplier_id' => Contact::factory(),
            'purchase_order_id' => PurchaseOrder::factory(),
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
