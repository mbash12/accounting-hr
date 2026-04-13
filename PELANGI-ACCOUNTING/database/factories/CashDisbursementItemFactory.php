<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\CashDisbursement;
use App\Models\CashDisbursementItem;

class CashDisbursementItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashDisbursementItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->word(),
            'description' => fake()->text(),
            'cash_disbursement_id' => CashDisbursement::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
