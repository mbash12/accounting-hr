<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\StockOpname;
use App\Models\User;
use App\Models\Warehouse;

class StockOpnameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockOpname::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["draft","in_progress","completed","approved","rejected"]),
            'warehouse_id' => Warehouse::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
