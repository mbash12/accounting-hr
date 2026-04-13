<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Unit;

class SalesReturnItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesReturnItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'description' => fake()->text(),
            'return_reason' => fake()->text(),
            'sales_return_id' => SalesReturn::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
