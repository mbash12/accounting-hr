<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Unit;
use App\Models\User;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'description' => fake()->text(),
            'cost_price' => fake()->randomFloat(2, 0, 9999999999999.99),
            'selling_price' => fake()->randomFloat(2, 0, 9999999999999.99),
            'reorder_level' => fake()->randomFloat(2, 0, 9999999999999.99),
            'max_stock' => fake()->randomFloat(2, 0, 9999999999999.99),
            'weight' => fake()->randomFloat(3, 0, 9999999.999),
            'product_type' => fake()->regexify('[A-Za-z0-9]{50}'),
            'is_active' => fake()->boolean(),
            'image' => fake()->word(),
            'unit_id' => Unit::factory(),
            'product_group_id' => ProductGroup::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
        ];
    }
}
