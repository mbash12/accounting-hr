<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Company;
use App\Models\Tax;
use App\Models\User;

class TaxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tax::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'tax_percentage' => fake()->randomFloat(2, 0, 99999999.99),
            'tax_type' => fake()->randomElement(["vat","sales_tax","service_tax","withholding_tax","excise_tax"]),
            'is_purchase_tax' => fake()->boolean(),
            'is_sales_tax' => fake()->boolean(),
            'effective_date' => fake()->date(),
            'expiry_date' => fake()->date(),
            'compound_tax' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'purchase_account_id' => Account::factory(),
            'sales_account_id' => Account::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
