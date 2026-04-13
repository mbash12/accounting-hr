<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AdvanceDisbursement;
use App\Models\AdvanceDisbursementItem;
use App\Models\TransactionClassification;

class AdvanceDisbursementItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvanceDisbursementItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->word(),
            'description' => fake()->text(),
            'advance_disbursement_id' => AdvanceDisbursement::factory(),
            'transaction_classification_id' => TransactionClassification::factory(),
        ];
    }
}
