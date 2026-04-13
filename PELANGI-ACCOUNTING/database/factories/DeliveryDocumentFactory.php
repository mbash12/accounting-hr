<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\DeliveryDocument;
use App\Models\Expedition;
use App\Models\Project;
use App\Models\SalesOrder;
use App\Models\User;

class DeliveryDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryDocument::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'delivery_type' => fake()->randomElement(["full","partial","return","replacement","sample"]),
            'date' => fake()->date(),
            'is_closed' => fake()->boolean(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'delivery_status' => fake()->randomElement(["pending","picked","in_transit","delivered","failed","cancelled"]),
            'tracking_number' => fake()->regexify('[A-Za-z0-9]{100}'),
            'bast_document' => fake()->word(),
            'tpb_document' => fake()->word(),
            'dispatch_time' => fake()->dateTime(),
            'completion_time' => fake()->dateTime(),
            'customer_id' => Contact::factory(),
            'sales_order_id' => SalesOrder::factory(),
            'job_id' => Project::factory(),
            'expedition_id' => Expedition::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
