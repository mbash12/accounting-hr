<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\PeriodClosing;
use App\Models\User;

class PeriodClosingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PeriodClosing::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'period_type' => fake()->randomElement(["daily","weekly","monthly","quarterly","yearly"]),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'status' => fake()->randomElement(["pending","in_progress","completed","failed"]),
            'closed_at' => fake()->dateTime(),
            'description' => fake()->text(),
            'closed_by_user_id' => User::factory(),
            'company_id' => Company::factory(),
        ];
    }
}
