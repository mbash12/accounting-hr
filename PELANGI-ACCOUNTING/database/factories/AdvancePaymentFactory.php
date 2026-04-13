<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AdvancePayment;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;

class AdvancePaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvancePayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'advance_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'date' => fake()->date(),
            'amount' => fake()->word(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["pending","active","partially_used","fully_used","expired","cancelled"]),
            'used_amount' => fake()->word(),
            'remaining_amount' => fake()->word(),
            'expiry_date' => fake()->date(),
            'customer_id' => Contact::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
