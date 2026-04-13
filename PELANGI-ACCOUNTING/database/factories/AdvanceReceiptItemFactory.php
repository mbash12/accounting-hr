<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AdvanceReceipt;
use App\Models\AdvanceReceiptItem;
use App\Models\TransactionClassification;

class AdvanceReceiptItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvanceReceiptItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->word(),
            'description' => fake()->text(),
            'advance_receipt_id' => AdvanceReceipt::factory(),
            'transaction_classification_id' => TransactionClassification::factory(),
        ];
    }
}
