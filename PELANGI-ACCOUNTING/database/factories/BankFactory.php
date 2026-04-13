<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bank;
use App\Models\Company;
use App\Models\User;

class BankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'name' => fake()->name(),
            'logo' => fake()->word(),
            'country' => fake()->country(),
            'clearing_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'skn_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'is_active' => fake()->boolean(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
