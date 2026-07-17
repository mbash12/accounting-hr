<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\PeriodClosing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodClosingFactory extends Factory
{
    protected $model = PeriodClosing::class;

    public function definition(): array
    {
        $year = (int) now()->year;

        return [
            'period_type' => PeriodClosing::TYPE_YEARLY,
            'start_date' => "{$year}-01-01",
            'end_date' => "{$year}-12-31",
            'status' => PeriodClosing::STATUS_OPEN,
            'closed_at' => null,
            'description' => null,
            'closed_by_user_id' => null,
            'company_id' => Company::factory(),
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => PeriodClosing::STATUS_CLOSED,
            'closed_at' => now(),
            'closed_by_user_id' => User::factory(),
            'description' => 'Tutup Buku',
        ]);
    }
}
