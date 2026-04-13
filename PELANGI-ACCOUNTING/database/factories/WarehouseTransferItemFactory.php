<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Unit;
use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferItem;

class WarehouseTransferItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseTransferItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'description' => fake()->text(),
            'warehouse_transfer_id' => WarehouseTransfer::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
