<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Tax;
use App\Models\Unit;

class SalesOrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesOrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomFloat(2, 0, 9999999999999.99),
            'unit_price' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total' => fake()->randomFloat(2, 0, 9999999999999.99),
            'description' => fake()->text(),
            'discount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'discount_percentage' => fake()->randomFloat(2, 0, 999.99),
            'tax_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'delivered_quantity' => fake()->randomFloat(2, 0, 9999999999999.99),
            'sales_order_id' => SalesOrder::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'tax_id' => Tax::factory(),
        ];
    }
}
