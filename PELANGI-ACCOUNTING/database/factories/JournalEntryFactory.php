<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Department;
use App\Models\JournalEntry;
use App\Models\User;

class JournalEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JournalEntry::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'entry_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'date' => fake()->date(),
            'reference_no' => fake()->word(),
            'description' => fake()->text(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'status' => fake()->randomElement(["draft","posted","reversed","cancelled"]),
            'document_no' => fake()->word(),
            'document_date' => fake()->date(),
            'posted_at' => fake()->dateTime(),
            'department_id' => Department::factory(),
            'posted_by_user_id' => User::factory(),
            'company_id' => Company::factory(),
            'created_by_user_id' => User::factory(),
            'updated_by_user_id' => User::factory(),
        ];
    }
}
