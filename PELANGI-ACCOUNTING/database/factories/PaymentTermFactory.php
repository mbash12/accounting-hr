<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\PaymentTerm;
use App\Models\User;

class PaymentTermFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentTerm::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'due_days' => fake()->numberBetween(-10000, 10000),
            'is_active' => fake()->boolean(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
