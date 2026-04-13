<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'task_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'task_type' => fake()->randomElement(["milestone","deliverable","issue","bug_fix","feature","review"]),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'due_date' => fake()->date(),
            'status' => fake()->randomElement(["todo","in_progress","review","completed","cancelled"]),
            'completed_at' => fake()->dateTime(),
            'assigned_to_id' => User::factory(),
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
        ];
    }
}
