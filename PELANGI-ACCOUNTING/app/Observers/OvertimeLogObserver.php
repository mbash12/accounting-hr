<?php

namespace App\Observers;

use App\Models\OvertimeLog;
use App\Models\OvertimeRule;

class OvertimeLogObserver
{
    public function updated(OvertimeLog $overtimeLog): void
    {
        if (!$overtimeLog->isDirty('status') || $overtimeLog->status !== 'approved') {
            return;
        }

        $this->calculateAmount($overtimeLog);
    }

    private function calculateAmount(OvertimeLog $log): void
    {
        $employee = $log->employee()->first();
        if (!$employee || !$employee->basic_salary) {
            return;
        }

        $rule = OvertimeRule::where('department_id', $employee->department_id)
            ->where('is_active', true)
            ->first() ?? OvertimeRule::whereNull('department_id')
            ->where('is_default', true)
            ->first();

        if (!$rule) {
            return;
        }

        $hourlyRate = $employee->basic_salary / $rule->base_hourly_rate_divisor;

        if ($log->is_holiday) {
            $amount = $log->hours * $rule->holiday_multiplier * $hourlyRate;
        } else {
            if ($log->hours <= 1) {
                $amount = $log->hours * $rule->workday_first_hour_multiplier * $hourlyRate;
            } else {
                $amount = (1 * $rule->workday_first_hour_multiplier * $hourlyRate)
                        + (($log->hours - 1) * $rule->workday_subsequent_hour_multiplier * $hourlyRate);
            }
        }

        $log->withoutEvents(function () use ($log, $amount) {
            $log->updateQuietly(['calculated_amount' => round($amount, 2)]);
        });
    }
}
