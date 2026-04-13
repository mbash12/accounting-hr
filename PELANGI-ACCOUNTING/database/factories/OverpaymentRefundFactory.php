<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\OverpaymentRefund;
use App\Models\ReceivablePayment;
use App\Models\User;

class OverpaymentRefundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OverpaymentRefund::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'amount' => fake()->word(),
            'status' => fake()->randomElement(["pending","processed","failed","cancelled"]),
            'customer_id' => Contact::factory(),
            'receivable_payment_id' => ReceivablePayment::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
