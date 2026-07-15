<?php

namespace App\Observers;

use App\Models\OvertimeLog;
use App\Models\OvertimeRule;

class OvertimeLogObserver
{
    public function updated(OvertimeLog $overtimeLog): void
    {
        // Recalculate when:
        // 1. Status changes to 'approved'
        // 2. hours or is_holiday changes while status is already 'approved'
        $statusApproved = $overtimeLog->status === 'approved';
        $justApproved = $overtimeLog->isDirty('status') && $statusApproved;
        $fieldsChangedWhileApproved = $statusApproved
            && ($overtimeLog->isDirty('hours') || $overtimeLog->isDirty('is_holiday'));

        if (! $justApproved && ! $fieldsChangedWhileApproved) {
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
            ->first()
            ?? OvertimeRule::where('is_default', true)
                ->where('is_active', true)
                ->orderByRaw('department_id IS NULL DESC')
                ->orderBy('id')
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
