<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

class InventoryTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'transaction_type' => fake()->randomElement(["purchase","sale","return_in","return_out","adjustment","transfer_in","transfer_out","opname"]),
            'quantity' => fake()->word(),
            'unit_cost' => fake()->word(),
            'total_cost' => fake()->word(),
            'reference_no' => fake()->word(),
            'date' => fake()->date(),
            'description' => fake()->text(),
            'batch_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'expiry_date' => fake()->date(),
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
