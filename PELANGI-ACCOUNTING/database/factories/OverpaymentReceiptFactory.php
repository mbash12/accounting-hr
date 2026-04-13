<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\OverpaymentReceipt;
use App\Models\PayablePayment;
use App\Models\User;

class OverpaymentReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OverpaymentReceipt::class;

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
            'supplier_id' => Contact::factory(),
            'payable_payment_id' => PayablePayment::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
