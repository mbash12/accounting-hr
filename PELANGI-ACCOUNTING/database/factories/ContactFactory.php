<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Contact;
use App\Models\PaymentTerm;
use App\Models\User;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'tax' => fake()->regexify('[A-Za-z0-9]{50}'),
            'contact_code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'contact_person' => fake()->regexify('[A-Za-z0-9]{200}'),
            'is_customer' => fake()->boolean(),
            'is_supplier' => fake()->boolean(),
            'is_employee' => fake()->boolean(),
            'is_sales' => fake()->boolean(),
            'credit_limit' => fake()->randomFloat(2, 0, 9999999999999.99),
            'is_active' => fake()->boolean(),
            'billing_address_line_1' => fake()->regexify('[A-Za-z0-9]{255}'),
            'billing_address_line_2' => fake()->regexify('[A-Za-z0-9]{255}'),
            'billing_city' => fake()->regexify('[A-Za-z0-9]{100}'),
            'billing_state' => fake()->regexify('[A-Za-z0-9]{100}'),
            'billing_postal_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'billing_country' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_address_line_1' => fake()->regexify('[A-Za-z0-9]{255}'),
            'delivery_address_line_2' => fake()->regexify('[A-Za-z0-9]{255}'),
            'delivery_city' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_state' => fake()->regexify('[A-Za-z0-9]{100}'),
            'delivery_postal_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'delivery_country' => fake()->regexify('[A-Za-z0-9]{100}'),
            'supporting_document' => fake()->word(),
            'payment_term_id' => PaymentTerm::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
