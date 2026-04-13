<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Tax;
use App\Models\Unit;

class PurchaseOrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseOrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'unit_price' => fake()->word(),
            'total' => fake()->word(),
            'description' => fake()->text(),
            'received_quantity' => fake()->word(),
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'tax_id' => Tax::factory(),
        ];
    }
}
