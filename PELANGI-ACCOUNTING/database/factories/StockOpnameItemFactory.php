<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\Unit;

class StockOpnameItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockOpnameItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'book_stock' => fake()->word(),
            'physical_stock' => fake()->word(),
            'difference' => fake()->word(),
            'description' => fake()->text(),
            'stock_opname_id' => StockOpname::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
