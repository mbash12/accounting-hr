<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PayablePayment;
use App\Models\PayablePaymentItem;
use App\Models\PurchaseInvoice;

class PayablePaymentItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PayablePaymentItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'amount' => fake()->word(),
            'paid_amount' => fake()->word(),
            'discount_amount' => fake()->word(),
            'write_off_amount' => fake()->word(),
            'set_payment' => fake()->word(),
            'payable_payment_id' => PayablePayment::factory(),
            'purchase_invoice_id' => PurchaseInvoice::factory(),
        ];
    }
}
