<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\ShiftSchedule;
use App\Models\ShiftType;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShiftScheduleImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $updated  = 0;
    public int $skipped  = 0;
    public array $errors = [];

    public function __construct(
        public int $year,
        public int $month,
        public ?int $companyId = null,
    ) {}

    public function collection(Collection $rows)
    {
        $header = collect($rows->first() ? array_keys($rows->first()->toArray()) : [])
            ->map(fn ($h) => strtolower(trim((string) $h)))
            ->all();

        $isWide = ! in_array('date', $header, true)
            && count(array_filter($header, fn ($h) => is_numeric(trim((string) $h)))) > 3;

        $typeMap = $this->loadShiftTypeMap();
        $empMap  = $this->loadEmployeeMap();
        $firstDate = CarbonImmutable::create($this->year, $this->month, 1);
        $daysIn    = (int) $firstDate->daysInMonth;
        $holidayMap = $this->loadHolidayMap($firstDate, $firstDate->endOfMonth());

        $isWide
            ? $this->importWide($rows, $typeMap, $empMap, $firstDate, $daysIn, $holidayMap)
            : $this->importLong($rows, $typeMap, $empMap, $holidayMap);
    }

    private function importLong(Collection $rows, array $typeMap, array $empMap, array $holidayMap): void
    {
        $rowNo = 1;
        foreach ($rows as $row) {
            $rowNo++;
            $empKey  = (string) ($row['employee_id'] ?? '');
            $dateRaw = (string) ($row['date']        ?? '');
            $code    = trim((string) ($row['shift_code'] ?? ''));

            $empId = $empMap[$empKey] ?? $empMap['0' . ltrim($empKey, '0')] ?? null;
            if (!$empId) { $this->skip($rowNo, "unknown employee '{$empKey}'"); continue; }
            if (! $dateRaw) { $this->skip($rowNo, 'missing date'); continue; }
            $type = $typeMap[$code] ?? null;
            if (!$type) { $this->skip($rowNo, "unknown shift code '{$code}'"); continue; }

            $this->upsert(
                $empId, $dateRaw, $type, $holidayMap[$dateRaw] ?? null
            );
        }
    }

    private function importWide(Collection $rows, array $typeMap, array $empMap, CarbonImmutable $firstDate, int $daysIn, array $holidayMap): void
    {
        $rowNo = 1;
        foreach ($rows as $row) {
            $rowNo++;
            $empKey = trim((string) ($row['employee_id'] ?? ''));
            // Stop parsing once we reach the legend / footer section
            if ($empKey === '' || strcasecmp($empKey, 'Legend') === 0 || strcasecmp($empKey, 'Code') === 0) {
                return;
            }
            $empId  = $empMap[$empKey] ?? $empMap['0' . ltrim($empKey, '0')] ?? null;
            if (!$empId) { $this->skip($rowNo, "unknown employee '{$empKey}'"); continue; }

            foreach ($row as $k => $v) {
                $k = strtolower(trim((string) $k));
                if (! ctype_digit($k)) continue;
                $day = (int) $k;
                if ($day < 1 || $day > $daysIn) continue;
                $code = trim((string) $v);
                if ($code === '') continue;
                $type = $typeMap[$code] ?? null;
                if (!$type) { $this->skip($rowNo, "day {$day}: unknown shift code '{$code}'"); continue; }

                $dateStr = $firstDate->setDay($day)->toDateString();
                $this->upsert(
                    $empId, $dateStr, $type, $holidayMap[$dateStr] ?? null
                );
            }
        }
    }

    private function upsert(int $empId, string $dateStr, ShiftType $type, ?array $holiday): void
    {
        $isHoliday = (bool) $holiday;
        $isOff     = (bool) $type->is_off || $isHoliday;

        // Cek row yang ada, termasuk yg soft-deleted (supaya unique constraint gak error)
        $existing = ShiftSchedule::withTrashed()
            ->where('employee_id', $empId)
            ->where('date', $dateStr)
            ->first();

        if ($existing) {
            // Restore jika soft-deleted, lalu update
            if ($existing->trashed()) {
                $existing->restore();
            }
            $existing->update([
                'company_id'    => $this->companyId,
                'shift_type_id' => $type->id,
                'shift_code'    => $type->code,
                'is_off'        => $isOff,
                'is_holiday'    => $isHoliday,
                'holiday_name'  => $holiday['name'] ?? null,
            ]);
            $this->updated++;
        } else {
            ShiftSchedule::create([
                'employee_id'    => $empId,
                'company_id'     => $this->companyId,
                'shift_type_id'  => $type->id,
                'date'           => $dateStr,
                'shift_code'     => $type->code,
                'is_off'         => $isOff,
                'is_holiday'     => $isHoliday,
                'holiday_name'   => $holiday['name'] ?? null,
            ]);
            $this->inserted++;
        }
    }

    private function skip(int $rowNo, string $reason): void
    {
        $this->skipped++;
        $this->errors[] = "row {$rowNo}: {$reason}";
    }

    private function loadShiftTypeMap(): array
    {
        $companyId = $this->companyId;
        return ShiftType::query()
            ->where('is_active', true)
            ->when($companyId, function ($q) use ($companyId) {
                $q->where(function ($qq) use ($companyId) {
                    $qq->where('company_id', $companyId)->orWhereNull('company_id');
                });
            })
            ->get()
            ->groupBy('code')
            ->map(function ($g) use ($companyId) {
                return $g->firstWhere('company_id', $companyId) ?? $g->first();
            })
            ->keyBy('code')
            ->all();
    }

    private function loadEmployeeMap(): array
    {
        return Employee::query()
            ->select(['id', 'employee_id'])
            ->get()
            ->mapWithKeys(fn ($e) => [$e->employee_id => $e->id])
            ->toArray();
    }

    private function loadHolidayMap(CarbonImmutable $from, CarbonImmutable $to): array
    {
        if (!class_exists(Holiday::class)) {
            return [];
        }
        $companyId = $this->companyId;
        return Holiday::query()
            ->when($companyId, function ($q) use ($companyId) {
                $q->where(function ($qq) use ($companyId) {
                    $qq->where('company_id', $companyId)->orWhereNull('company_id');
                });
            })
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
