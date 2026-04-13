<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Unit;

class PurchaseReturnItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseReturnItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'description' => fake()->text(),
            'return_reason' => fake()->text(),
            'purchase_return_id' => PurchaseReturn::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
