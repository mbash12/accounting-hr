<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AdvancePayment;
use App\Models\AdvancePaymentAllocation;

class AdvancePaymentAllocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvancePaymentAllocation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'allocated_amount' => fake()->word(),
            'allocated_date' => fake()->date(),
            'notes' => fake()->text(),
            'advance_payment_id' => AdvancePayment::factory(),
        ];
    }
}
