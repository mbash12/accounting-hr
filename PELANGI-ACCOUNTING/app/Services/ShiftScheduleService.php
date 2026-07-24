<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\ShiftSchedule;
use App\Models\ShiftType;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShiftScheduleService
{
    /**
     * Compare actual attendance against the planned shift on each day,
     * and persist late_minutes / early_departure_minutes on Attendance rows.
     *
     * @return array{updated:int, missing_shift:int, on_time:int, late:int, early_leave:int}
     */
    public function recalculateLate(int $year, int $month, ?int $companyId = null): array
    {
        $start = CarbonImmutable::create($year, $month, 1)->startOfDay();
        $end   = $start->endOfMonth();

        $schedules = ShiftSchedule::query()
            ->with('shiftType')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($s) => $s->employee_id . '|' . $s->date->format('Y-m-d'));

        $updated = 0;
        $missing = 0;
        $onTime  = 0;
        $late    = 0;
        $early   = 0;
        $graceMin = (int) config('attendance.late_grace_minutes', 5);

        DB::transaction(function () use ($schedules, $companyId, $graceMin, $start, $end, &$updated, &$missing, &$onTime, &$late, &$early) {
            Attendance::query()
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->whereNotNull('check_in')
                ->orderBy('id')
                ->chunk(200, function ($rows) use ($schedules, $graceMin, &$updated, &$missing, &$onTime, &$late, &$early) {
                    foreach ($rows as $a) {
                        $key = $a->employee_id . '|' . $a->date->format('Y-m-d');
                        $sched = $schedules->get($key);
                        if (!$sched || !$sched->shiftType) {
                            $missing++;
                            continue;
                        }

                        $expectedIn  = $sched->shiftType->start_time;
                        $expectedOut = $sched->shiftType->end_time;

                        $lateMin  = 0;
                        $earlyMin = 0;

                        // DB stores check_in / check_out as TIME (no date) — pull the raw value
                        $rawIn  = $a->getAttributes()['check_in']  ?? null;
                        $rawOut = $a->getAttributes()['check_out'] ?? null;
                        $dateStr = $a->date->format('Y-m-d');

                        if ($expectedIn && $rawIn) {
                            $expTs    = strtotime($dateStr . ' ' . $expectedIn);
                            $actualTs = strtotime($dateStr . ' ' . substr((string) $rawIn, 0, 8));
                            $diffSec  = $actualTs - $expTs;
                            $lateMin  = max(0, intdiv((int) $diffSec, 60) - $graceMin);
                        }
                        if ($expectedOut && $rawOut) {
                            $expTs    = strtotime($dateStr . ' ' . $expectedOut);
                            $actualTs = strtotime($dateStr . ' ' . substr((string) $rawOut, 0, 8));
                            $diffSec  = $expTs - $actualTs;            // positive when leaving early
                            $earlyMin = max(0, intdiv((int) $diffSec, 60) - $graceMin);
                        }

                        $dirty = false;
                        if ($a->late_minutes !== $lateMin) { $a->late_minutes = $lateMin; $dirty = true; }
                        if ($a->early_departure_minutes !== $earlyMin) { $a->early_departure_minutes = $earlyMin; $dirty = true; }
                        if ($dirty) {
                            $a->save();
                            $updated++;
                        }
                        if ($lateMin > 0) $late++; else $onTime++;
                        if ($earlyMin > 0) $early++;
                    }
                });
        });

        return [
            'updated'      => $updated,
            'missing_shift'=> $missing,
            'on_time'      => $onTime,
            'late'         => $late,
            'early_leave'  => $early,
        ];
    }

    /**
     * Build the in-memory grid for a month (planned schedule only).
     *
     * @return array{
     *   year:int, month:int, days_in_month:int, first_dow:int,
     *   employees: Collection,
     *   grid: array<int, array<int, array>>,
     *   holidays: array<string, array{name:string, is_cuti_bersama:bool}>,
     *   legend: Collection
     * }
     */
    public function buildMonthGrid(int $year, int $month, ?int $companyId = null, ?int $departmentId = null): array
    {
        $start  = CarbonImmutable::create($year, $month, 1)->startOfDay();
        $end    = $start->endOfMonth();
        $daysIn = (int) $start->daysInMonth;

        $schedules = ShiftSchedule::query()
            ->with(['employee', 'shiftType'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->when($departmentId, function ($q) use ($departmentId) {
                $q->whereHas('employee', fn ($qq) => $qq->where('department_id', $departmentId));
            })
            ->orderBy('employee_id')
            ->orderBy('date')
            ->get();

        $employees = $schedules->pluck('employee')->unique('id')->sortBy('name')->values();

        $grid = [];
        foreach ($employees as $emp) {
            $grid[$emp->id] = array_fill(1, $daysIn, null);
        }
        foreach ($schedules as $s) {
            $day = (int) $s->date->format('j');
            $grid[$s->employee_id][$day] = [
                'id'           => $s->id,
                'code'         => $s->shift_code,
                'color'        => $s->shiftType?->color      ?? '#ffffff',
                'text_color'   => $s->shiftType?->text_color ?? '#000000',
                'is_off'       => (bool) $s->is_off,
                'is_holiday'   => (bool) $s->is_holiday,
                'holiday_name' => $s->holiday_name,
                'name'         => $s->shiftType?->name,
                'start_time'   => $s->shiftType?->start_time,
                'end_time'     => $s->shiftType?->end_time,
            ];
        }

        $holidayMap = $this->loadHolidayMap($start, $end, $companyId);

        $legend = ShiftType::query()
            ->when($companyId, fn ($q) => $q->where(function ($qq) use ($companyId) {
                $qq->where('company_id', $companyId)->orWhereNull('company_id');
            }))
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return [
            'year'         => $year,
            'month'        => $month,
            'month_name'   => $start->format('F'),
            'days_in_month'=> $daysIn,
            'first_dow'    => (int) $start->dayOfWeekIso,
            'employees'    => $employees,
            'grid'         => $grid,
            'holidays'     => $holidayMap,
            'legend'       => $legend,
        ];
    }

    public function clearMonth(int $year, int $month, ?int $companyId = null): int
    {
        $start = CarbonImmutable::create($year, $month, 1);
        $end   = $start->endOfMonth();
        return ShiftSchedule::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->delete();
    }

    /**
     * @return array<string, array{name:string, is_cuti_bersama:bool}>
     */
    private function loadHolidayMap(CarbonImmutable $from, CarbonImmutable $to, ?int $companyId): array
    {
        if (!class_exists(Holiday::class)) {
            return [];
        }
        return Holiday::query()
            ->when($companyId, fn ($q) => $q->where(function ($qq) use ($companyId) {
                $qq->where('company_id', $companyId)->orWhereNull('company_id');
            }))
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get()
            ->mapWithKeys(fn ($h) => [
                $h->date->format('Y-m-d') => [
                    'name'             => $h->name,
                    'is_cuti_bersama'  => (bool) $h->is_cuti_bersama,
                ],
            ])
            ->toArray();
    }



}
