<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BusinessType;
use App\Models\Company;
use App\Models\User;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'tax_id' => fake()->regexify('[A-Za-z0-9]{50}'),
            'fiscal_year_start' => fake()->date(),
            'fiscal_year_end' => fake()->date(),
            'tax_period' => fake()->randomElement(["monthly","quarterly","semi_annual","annual"]),
            'is_active' => fake()->boolean(),
            'settings' => '{}',
            'billing_address_line_1' => fake()->regexify('[A-Za-z0-9]{255}'),
            'billing_address_line_2' => fake()->regexify('[A-Za-z0-9]{255}'),
            'billing_city' => fake()->regexify('[A-Za-z0-9]{100}'),
            'billing_state' => fake()->regexify('[A-Za-z0-9]{100}'),
            'billing_postal_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'billing_country' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_address_line_1' => fake()->regexify('[A-Za-z0-9]{255}'),
            'delivery_address_line_2' => fake()->regexify('[A-Za-z0-9]{255}'),
            'delivery_city' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_state' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_postal_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'delivery_country' => fake()->regexify('[A-Za-z0-9]{100}'),
            'photo' => fake()->word(),
            'business_type_id' => BusinessType::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
