<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;

class GoodsReceiptItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoodsReceiptItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'description' => fake()->text(),
            'batch_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'expiry_date' => fake()->date(),
            'unit_cost' => fake()->word(),
            'goods_receipt_id' => GoodsReceipt::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}
