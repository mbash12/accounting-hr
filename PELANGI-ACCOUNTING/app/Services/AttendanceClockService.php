<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceClock;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceClockService
{
    private const ATTENDANCE_TIMEZONE = 'Asia/Jakarta';

    /**
     * Record a clock tick and refresh the parent attendance summary.
     */
    public function recordClock(
        int $employeeId,
        string $date,
        string $type,
        Carbon|string $clockedAt,
        string $source = AttendanceClock::SOURCE_APP,
        array $meta = []
    ): Attendance {
        $attendance = Attendance::query()
            ->where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();

        if (! $attendance) {
            $attendance = Attendance::query()->create([
                'employee_id' => $employeeId,
                'date' => $date,
                'company_id' => $meta['company_id'] ?? null,
                'status' => 'present',
            ]);
        }

        if (empty($attendance->company_id) && ! empty($meta['company_id'])) {
            $attendance->company_id = $meta['company_id'];
            $attendance->save();
        }

        $clockedAtCarbon = $clockedAt instanceof Carbon
            ? $clockedAt
            : Carbon::parse($clockedAt);

        // Deduplicate exact same timestamp + type + source.
        $duplicate = AttendanceClock::query()
            ->where('attendance_id', $attendance->id)
            ->where('type', $type)
            ->where('source', $source)
            ->where('clocked_at', $clockedAtCarbon)
            ->exists();

        if ($duplicate) {
            return $this->syncSummary($attendance);
        }

        AttendanceClock::create([
            'attendance_id' => $attendance->id,
            'type' => $type,
            'clocked_at' => $clockedAtCarbon,
            'source' => $source,
            'latitude' => $meta['latitude'] ?? null,
            'longitude' => $meta['longitude'] ?? null,
            'photo_path' => $meta['photo_path'] ?? null,
            'notes' => $meta['notes'] ?? null,
        ]);

        return $attendance->fresh(['clocks', 'employee.department']);
    }

    /**
     * Derive day bounds from all clock ticks: very first and very last, any source.
     *
     * @param  Collection<int, AttendanceClock>  $clocks
     * @return array{check_in: ?Carbon, check_out: ?Carbon, late_minutes: int}
     */
    public function summarizeClocks(Collection $clocks, ?Employee $employee): array
    {
        $sorted = $clocks->sortBy('clocked_at')->values();

        if ($sorted->isEmpty()) {
            return [
                'check_in' => null,
                'check_out' => null,
                'late_minutes' => 0,
            ];
        }

        $first = $sorted->first();
        $last = $sorted->count() > 1 ? $sorted->last() : null;
        $checkIn = $first->clocked_at;
        $checkOut = ($last && $last->clocked_at->gt($checkIn)) ? $last->clocked_at : null;

        return [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'late_minutes' => $this->calculateLateMinutes($checkIn, $employee),
        ];
    }

    /**
     * Recompute check_in/check_out from all clock ticks (first & last overall).
     */
    public function syncSummary(Attendance $attendance): Attendance
    {
        $attendance = $attendance->fresh(['employee.department']);
        $attendance->load('clocks');
        $summary = $this->summarizeClocks($attendance->clocks, $attendance->employee);

        if ($summary['check_in'] === null) {
            $attendance->update([
                'check_in' => null,
                'check_out' => null,
                'late_minutes' => 0,
                'early_departure_minutes' => 0,
                'lat_in' => null,
                'lng_in' => null,
                'lat_out' => null,
                'lng_out' => null,
                'photo_in_path' => null,
                'photo_out_path' => null,
                'notes_in' => null,
                'notes_out' => null,
            ]);

            return $attendance->fresh();
        }

        $clocks = $attendance->clocks->sortBy('clocked_at')->values();
        $first = $clocks->first();
        $last = $clocks->count() > 1 ? $clocks->last() : null;
        $firstIn = $clocks->firstWhere('type', AttendanceClock::TYPE_IN) ?? $first;
        $lastOut = $clocks->reverse()->firstWhere('type', AttendanceClock::TYPE_OUT) ?? $last;

        $earlyMinutes = $this->calculateEarlyDepartureMinutes($summary['check_out'], $attendance->employee);

        $status = $attendance->status;
        if (! in_array($status, ['permit', 'leave', 'absent'], true)) {
            $status = ($summary['check_in'] && $summary['late_minutes'] > 0)
                ? 'late'
                : ($summary['check_in'] || $summary['check_out'] ? 'present' : 'absent');
        }

        $attendance->update([
            'check_in' => $summary['check_in'],
            'check_out' => $summary['check_out'],
            'late_minutes' => $summary['late_minutes'],
            'early_departure_minutes' => $earlyMinutes,
            'lat_in' => $firstIn?->latitude,
            'lng_in' => $firstIn?->longitude,
            'lat_out' => $lastOut?->latitude,
            'lng_out' => $lastOut?->longitude,
            'photo_in_path' => $firstIn?->photo_path,
            'photo_out_path' => $lastOut?->photo_path,
            'notes_in' => $firstIn?->notes,
            'notes_out' => $lastOut?->notes,
            'status' => $status,
        ]);

        return $attendance->fresh(['clocks']);
    }

    /**
     * @return Collection<int, string>
     */
    public function sourceLabels(Attendance $attendance): Collection
    {
        return $attendance->clocks
            ->pluck('source')
            ->unique()
            ->values()
            ->map(fn (string $source) => AttendanceClock::sourceOptions()[$source] ?? $source);
    }

    private function calculateLateMinutes(mixed $checkIn, ?Employee $employee): int
    {
        if (! $checkIn || ! $employee) {
            return 0;
        }

        $workStartTime = $employee->department?->work_start_time ?: '08:00:00';
        $startMinutes = $this->parseDepartmentClockMinutes($workStartTime, 8 * 60);
        $checkInMinutes = $this->extractClockMinutes($checkIn);

        if ($checkInMinutes === null) {
            return 0;
        }

        return max(0, $checkInMinutes - $startMinutes);
    }

    private function calculateEarlyDepartureMinutes(mixed $checkOut, ?Employee $employee): int
    {
        if (! $checkOut || ! $employee) {
            return 0;
        }

        $workEndTime = $employee->department?->work_end_time ?: '17:00:00';
        $endMinutes = $this->parseDepartmentClockMinutes($workEndTime, 17 * 60);
        $checkOutMinutes = $this->extractClockMinutes($checkOut);

        if ($checkOutMinutes === null) {
            return 0;
        }

        return max(0, $endMinutes - $checkOutMinutes);
    }

    private function parseClockMinutes(mixed $value, int $fallbackMinutes): int
    {
        if (! is_string($value) || trim($value) === '') {
            return $fallbackMinutes;
        }

        $raw = trim($value);
        foreach (['H:i:s', 'H:i', 'h:i:s A', 'h:i A'] as $format) {
            try {
                $time = Carbon::createFromFormat($format, $raw, self::ATTENDANCE_TIMEZONE);

                return ((int) $time->format('H')) * 60 + (int) $time->format('i');
            } catch (\Throwable) {
            }
        }

        return $fallbackMinutes;
    }

    private function parseDepartmentClockMinutes(mixed $value, int $fallbackMinutes): int
    {
        $rawMinutes = $this->parseClockMinutes($value, $fallbackMinutes);
        $rawHour = intdiv($rawMinutes, 60);
        $fallbackHour = intdiv($fallbackMinutes, 60);
        $shouldShiftFromUtcClock = $fallbackHour <= 12
            ? $rawHour < 4
            : $rawHour < 13;

        return $shouldShiftFromUtcClock
            ? ($rawMinutes + (7 * 60)) % (24 * 60)
            : $rawMinutes;
    }

    private function extractClockMinutes(mixed $value): ?int
    {
        if ($value instanceof Carbon) {
            return ((int) $value->format('H')) * 60 + (int) $value->format('i');
        }

        if (is_string($value)) {
            if (preg_match('/\b(\d{1,2}):(\d{2})\b/', $value, $matches)) {
                $hour = max(0, min(23, (int) $matches[1]));
                $minute = max(0, min(59, (int) $matches[2]));

                return ($hour * 60) + $minute;
            }

            try {
                $parsed = Carbon::parse($value);

                return ((int) $parsed->format('H')) * 60 + (int) $parsed->format('i');
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }
}
