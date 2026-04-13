<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;

class TaskUpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskUpdate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'update_text' => fake()->text(),
            'task_id' => Task::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
