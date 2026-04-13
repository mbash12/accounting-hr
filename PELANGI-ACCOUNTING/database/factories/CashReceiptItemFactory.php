<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\CashReceipt;
use App\Models\CashReceiptItem;

class CashReceiptItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashReceiptItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->word(),
            'description' => fake()->text(),
            'cash_receipt_id' => CashReceipt::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
