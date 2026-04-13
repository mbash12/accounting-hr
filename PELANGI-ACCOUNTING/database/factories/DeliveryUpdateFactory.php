<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DeliveryUpdate;
use App\Models\Product;
use App\Models\Task;
use App\Models\Unit;

class DeliveryUpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryUpdate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->word(),
            'status' => fake()->randomElement(["pending","in_progress","completed","delayed","cancelled"]),
            'date' => fake()->date(),
            'task_id' => Task::factory(),
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
