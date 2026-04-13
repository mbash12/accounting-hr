<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\InventoryAdjustment;
use App\Models\InventoryAdjustmentItem;
use App\Models\Product;
use App\Models\Unit;

class InventoryAdjustmentItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryAdjustmentItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'cost_of_goods_sold' => fake()->word(),
            'description' => fake()->text(),
            'inventory_adjustment_id' => InventoryAdjustment::factory(),
            'product_id' => Product::factory(),
            'account_id' => Account::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
