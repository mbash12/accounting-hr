<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;

class DeliveryDocumentItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryDocumentItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'total_quantity' => fake()->word(),
            'description' => fake()->text(),
            'delivery_allocation' => '{}',
            'delivery_document_id' => DeliveryDocument::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}
