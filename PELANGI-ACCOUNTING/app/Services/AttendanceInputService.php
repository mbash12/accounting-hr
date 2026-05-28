<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceClock;
use App\Models\BiometricEmployee;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Pgvector\Laravel\Vector;

class AttendanceInputService
{
    /** Cosine distance threshold for face recognition acceptance. Lower = stricter match. */
    private const FACE_MATCH_THRESHOLD = 0.20;

    public function __construct(
        private readonly AttendanceClockService $clockService,
    ) {}

    /**
     * Normalize mobile / legacy attendance payload into tap fields.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    public function normalizeEmployeePayload(array $validated): array
    {
        $type = $validated['type'] ?? null;
        $datetime = $validated['datetime'] ?? null;

        if (! empty($datetime)) {
            $parsed = Carbon::parse((string) $datetime);
            $validated['date'] = $validated['date'] ?? $parsed->toDateString();

            if ($type === AttendanceClock::TYPE_OUT) {
                $validated['check_out'] = $validated['check_out'] ?? $datetime;
            } else {
                $validated['check_in'] = $validated['check_in'] ?? $datetime;
            }
        }

        if (isset($validated['latitude']) || isset($validated['longitude'])) {
            if ($type === AttendanceClock::TYPE_OUT) {
                $validated['lat_out'] = $validated['lat_out'] ?? $validated['latitude'] ?? null;
                $validated['lng_out'] = $validated['lng_out'] ?? $validated['longitude'] ?? null;
            } else {
                $validated['lat_in'] = $validated['lat_in'] ?? $validated['latitude'] ?? null;
                $validated['lng_in'] = $validated['lng_in'] ?? $validated['longitude'] ?? null;
            }
        }

        if (isset($validated['note'])) {
            $note = (string) $validated['note'];
            if ($type === AttendanceClock::TYPE_OUT) {
                $validated['notes_out'] = $validated['notes_out'] ?? $note;
            } else {
                $validated['notes_in'] = $validated['notes_in'] ?? $note;
            }
            $validated['notes'] = $validated['notes'] ?? $note;
        }

        if (isset($validated['attachment'])) {
            $attachmentPath = $this->normalizeAttachmentPath((string) $validated['attachment']);
            if ($type === AttendanceClock::TYPE_OUT) {
                $validated['photo_out_path'] = $validated['photo_out_path'] ?? $attachmentPath;
            } else {
                $validated['photo_in_path'] = $validated['photo_in_path'] ?? $attachmentPath;
            }
        }

        if (! isset($validated['source'])) {
            $validated['source'] = AttendanceClock::SOURCE_APP;
        }

        return $validated;
    }

    /**
     * Record one or more clock taps from an employee app payload.
     *
     * @param  array<string, mixed>  $validated
     */
    public function recordEmployeePayload(Employee $employee, array $validated, ?string $photoPath = null): Attendance
    {
        $validated = $this->normalizeEmployeePayload($validated);
        $date = (string) $validated['date'];
        $source = (string) $validated['source'];
        $type = $validated['type'] ?? null;
        $metaBase = ['company_id' => $employee->company_id];
        $attendance = null;

        // Face recognition validation for app-sourced clocks with a photo.
        if ($source === AttendanceClock::SOURCE_APP && $photoPath) {
            $this->validateFaceMatch($employee, $photoPath);
        }

        if ($this->isSingleTapPayload($validated)) {
            $clockType = $type === AttendanceClock::TYPE_OUT
                ? AttendanceClock::TYPE_OUT
                : AttendanceClock::TYPE_IN;

            $attendance = $this->clockService->recordClock(
                $employee->id,
                $date,
                $clockType,
                $validated['datetime'],
                $source,
                array_merge($metaBase, $this->tapMeta($validated, $clockType, $photoPath))
            );
        } else {
            if (! empty($validated['check_in'])) {
                $attendance = $this->clockService->recordClock(
                    $employee->id,
                    $date,
                    AttendanceClock::TYPE_IN,
                    $validated['check_in'],
                    $source,
                    array_merge($metaBase, $this->tapMeta($validated, AttendanceClock::TYPE_IN, $photoPath))
                );
            }

            if (! empty($validated['check_out'])) {
                $attendance = $this->clockService->recordClock(
                    $employee->id,
                    $date,
                    AttendanceClock::TYPE_OUT,
                    $validated['check_out'],
                    $source,
                    array_merge($metaBase, $this->tapMeta($validated, AttendanceClock::TYPE_OUT, $photoPath))
                );
            }
        }

        if (! $attendance) {
            throw ValidationException::withMessages([
                'datetime' => 'Waktu absensi wajib diisi.',
            ]);
        }

        $attendance->load('clocks');

        if (isset($validated['status'])) {
            $attendance->update(['status' => $validated['status']]);
            $attendance->refresh();
        }

        return $attendance;
    }

    /**
     * Record batch biometric clock data from the local broker.
     *
     * @param  list<array<string, mixed>>  $items
     * @return array{processed: int, skipped: int}
     */
    public function recordBiometricBatch(array $items): array
    {
        $processed = 0;
        $skipped = 0;

        foreach ($items as $item) {
            if ($this->recordBiometricItem($item)) {
                $processed++;
            } else {
                $skipped++;
            }
        }

        return [
            'processed' => $processed,
            'skipped' => $skipped,
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public function recordBiometricItem(array $item): bool
    {
        $biometric = BiometricEmployee::query()
            ->where('machine_user_id', $item['employee_id'])
            ->first();

        if (! $biometric?->employee_id) {
            return false;
        }

        $employee = $biometric->employee;
        $meta = ['company_id' => $employee?->company_id];
        $date = (string) ($item['date'] ?? Carbon::parse($item['datetime'] ?? $item['clock_in'] ?? $item['clock_out'] ?? now())->toDateString());

        if (! empty($item['datetime']) && ! empty($item['type'])) {
            $clockType = $item['type'] === AttendanceClock::TYPE_OUT
                ? AttendanceClock::TYPE_OUT
                : AttendanceClock::TYPE_IN;

            $this->clockService->recordClock(
                $biometric->employee_id,
                $date,
                $clockType,
                Carbon::parse($item['datetime']),
                AttendanceClock::SOURCE_BIOMETRIC,
                $meta
            );

            return true;
        }

        $recorded = false;

        if (! empty($item['clock_in'])) {
            $this->clockService->recordClock(
                $biometric->employee_id,
                $date,
                AttendanceClock::TYPE_IN,
                Carbon::parse($item['clock_in']),
                AttendanceClock::SOURCE_BIOMETRIC,
                $meta
            );
            $recorded = true;
        }

        if (! empty($item['clock_out'])) {
            $this->clockService->recordClock(
                $biometric->employee_id,
                $date,
                AttendanceClock::TYPE_OUT,
                Carbon::parse($item['clock_out']),
                AttendanceClock::SOURCE_BIOMETRIC,
                $meta
            );
            $recorded = true;
        }

        return $recorded;
    }

    /**
     * Transform an attendance record for API response, deriving check_in/check_out
     * from clocks instead of potentially stale DB columns.
     *
     * @return array<string, mixed>
     */
    public function transformAttendance(Attendance $attendance): array
    {
        $attendance->loadMissing('clocks');

        $clocks = $attendance->clocks->sortBy('clocked_at')->values();
        $checkIn = $clocks->first()?->clocked_at;
        $checkOut = $clocks->count() > 1 ? $clocks->last()?->clocked_at : null;
        if ($checkOut && $checkOut->lte($checkIn)) {
            $checkOut = null;
        }

        return [
            'id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'date' => $attendance->date?->format('Y-m-d'),
            'check_in' => $checkIn?->format('Y-m-d H:i:s'),
            'check_out' => $checkOut?->format('Y-m-d H:i:s'),
            'late_minutes' => $attendance->late_minutes,
            'early_departure_minutes' => $attendance->early_departure_minutes,
            'lat_in' => $attendance->lat_in,
            'lng_in' => $attendance->lng_in,
            'lat_out' => $attendance->lat_out,
            'lng_out' => $attendance->lng_out,
            'status' => $attendance->status,
            'photo_in_path' => $attendance->photo_in_path,
            'photo_out_path' => $attendance->photo_out_path,
            'notes' => $attendance->notes,
            'notes_in' => $attendance->notes_in,
            'notes_out' => $attendance->notes_out,
            'clocks' => $attendance->clocks
                ->sortBy('clocked_at')
                ->values()
                ->map(fn (AttendanceClock $clock) => [
                    'id' => $clock->id,
                    'type' => $clock->type,
                    'clocked_at' => $clock->clocked_at?->format('Y-m-d H:i:s'),
                    'source' => $clock->source,
                    'latitude' => $clock->latitude,
                    'longitude' => $clock->longitude,
                    'photo_path' => $clock->photo_path,
                    'notes' => $clock->notes,
                ])
                ->all(),
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
        ];
    }

    /**
     * Validate a clock-in/out photo against the employee's stored face vector.
     *
     * @throws ValidationException if the face does not match.
     */
    private function validateFaceMatch(Employee $employee, string $photoPath): void
    {
        $fullPath = storage_path('app/public/' . $photoPath);

        if (! file_exists($fullPath)) {
            throw ValidationException::withMessages([
                'photo' => 'Foto tidak ditemukan di path yang diberikan.',
            ]);
        }

        $newVectorArray = GeminiService::generateFaceVectorWithVertexAI($fullPath);
        $newVector = new Vector($newVectorArray);

        $distanceResult = Employee::query()
            ->selectRaw('(? <=> foto_vector) as distance', [$newVector])
            ->where('id', $employee->id)
            ->first();

        $distance = $distanceResult->distance ?? 1.0;

        if ($distance > self::FACE_MATCH_THRESHOLD) {
            throw ValidationException::withMessages([
                'photo' => 'Wajah tidak dikenali. Pastikan wajah Anda terlihat jelas, menghadap kamera, dan pencahayaan cukup.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function isSingleTapPayload(array $validated): bool
    {
        if (empty($validated['datetime']) || empty($validated['type'])) {
            return false;
        }

        $clockType = $validated['type'] === AttendanceClock::TYPE_OUT
            ? AttendanceClock::TYPE_OUT
            : AttendanceClock::TYPE_IN;

        if ($clockType === AttendanceClock::TYPE_OUT) {
            return empty($validated['check_in']);
        }

        return empty($validated['check_out']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function tapMeta(array $validated, string $clockType, ?string $photoPath): array
    {
        $isOut = $clockType === AttendanceClock::TYPE_OUT;

        return [
            'latitude' => $isOut
                ? ($validated['lat_out'] ?? null)
                : ($validated['lat_in'] ?? null),
            'longitude' => $isOut
                ? ($validated['lng_out'] ?? null)
                : ($validated['lng_in'] ?? null),
            'photo_path' => $photoPath
                ?? ($isOut ? ($validated['photo_out_path'] ?? null) : ($validated['photo_in_path'] ?? null)),
            'notes' => $isOut
                ? ($validated['notes_out'] ?? null)
                : ($validated['notes_in'] ?? $validated['notes'] ?? null),
        ];
    }

    private function normalizeAttachmentPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return $path;
        }

        if (Str::contains($path, '/storage/')) {
            return Str::after($path, '/storage/');
        }

        return ltrim($path, '/');
    }
}
