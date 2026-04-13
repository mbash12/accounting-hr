<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\CostCenter;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;

class JournalEntryItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JournalEntryItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'debit' => fake()->randomFloat(2, 0, 9999999999999.99),
            'credit' => fake()->randomFloat(2, 0, 9999999999999.99),
            'notes' => fake()->text(),
            'journal_entry_id' => JournalEntry::factory(),
            'account_id' => Account::factory(),
            'cost_center_id' => CostCenter::factory(),
        ];
    }
}
