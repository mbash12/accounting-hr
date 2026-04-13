<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\DocumentNumbering;
use App\Models\User;

class DocumentNumberingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocumentNumbering::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_type' => fake()->regexify('[A-Za-z0-9]{50}'),
            'prefix' => fake()->regexify('[A-Za-z0-9]{20}'),
            'format' => fake()->regexify('[A-Za-z0-9]{50}'),
            'next_number' => fake()->numberBetween(-10000, 10000),
            'reset_period' => fake()->randomElement(["never","daily","weekly","monthly","quarterly","yearly"]),
            'is_active' => fake()->boolean(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
