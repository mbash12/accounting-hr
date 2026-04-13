<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Milestone;
use App\Models\Project;

class MilestoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Milestone::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'milestone_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'milestone_type' => fake()->randomElement(["phase","deliverable","payment","review","acceptance"]),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'target_date' => fake()->date(),
            'actual_date' => fake()->date(),
            'pending_history' => '{}',
            'job_id' => Project::factory(),
            'company_id' => Company::factory(),
        ];
    }
}
