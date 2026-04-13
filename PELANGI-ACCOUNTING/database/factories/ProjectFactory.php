<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Project;
use App\Models\User;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'job_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'status' => fake()->randomElement(["planning","in_progress","on_hold","completed","cancelled"]),
            'customer_po_number' => fake()->regexify('[A-Za-z0-9]{100}'),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'total_value' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_invoiced' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_paid' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_delivered' => fake()->randomFloat(2, 0, 9999999999999.99),
            'customer_id' => Contact::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
