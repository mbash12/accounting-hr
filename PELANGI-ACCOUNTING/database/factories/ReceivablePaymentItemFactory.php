<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ReceivablePayment;
use App\Models\ReceivablePaymentItem;
use App\Models\SalesInvoice;

class ReceivablePaymentItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReceivablePaymentItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'paid_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'discount_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'write_off_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'set_payment' => fake()->randomFloat(2, 0, 9999999999999.99),
            'receivable_payment_id' => ReceivablePayment::factory(),
            'sales_invoice_id' => SalesInvoice::factory(),
        ];
    }
}
