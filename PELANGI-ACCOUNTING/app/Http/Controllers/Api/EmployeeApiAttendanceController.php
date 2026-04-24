<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeApiAttendanceController extends Controller
{
    private const ATTENDANCE_TIMEZONE = 'Asia/Jakarta';

    public function index(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $page = max((int) $request->query('page', 1), 1);
        $perPage = 10;

        $query = Attendance::query()
            ->where('employee_id', $employee->id)
            ->latest('id');

        $results = (clone $query)->count();
        $records = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (Attendance $attendance) => $this->transform($attendance))
            ->values();

        return response()->json([
            'records' => $records,
            'results' => $results,
        ]);
    }

    public function show(Request $request, Attendance $attendance): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        if ((int) $attendance->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($this->transform($attendance));
    }

    public function store(Request $request): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $request->attributes->get('employee');
        $employee->loadMissing('department');
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date'],
            'lat_in' => ['nullable', 'numeric'],
            'lng_in' => ['nullable', 'numeric'],
            'lat_out' => ['nullable', 'numeric'],
            'lng_out' => ['nullable', 'numeric'],
            'status' => ['nullable', 'in:present,late,absent,permit,leave'],
            'photo_in_path' => ['nullable', 'string'],
            'photo_out_path' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'notes_in' => ['nullable', 'string'],
            'notes_out' => ['nullable', 'string'],
            'photo' => ['nullable', 'file', 'max:10240'],
            // Compatibility fields used by legacy/manual clients.
            'type' => ['nullable', 'in:in,out'],
            'datetime' => ['nullable', 'date'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'note' => ['nullable', 'string'],
            'attachment' => ['nullable', 'string'],
        ]);

        $this->applyLegacyFieldMapping($request, $validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendances', 'public');
            $type = $validated['type'] ?? null;
            if ($type === 'out' || !empty($validated['check_out'])) {
                $validated['photo_out_path'] = $path;
            } elseif ($type === 'in' || !empty($validated['check_in'])) {
                $validated['photo_in_path'] = $path;
            } else {
                $validated['photo_in_path'] = $path;
            }
        }

        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('date', $validated['date'])
            ->first();

        $payload = $this->nonNullPayload($validated);
        $this->applyAttendanceMetrics($payload, $employee, $attendance);

        if ($attendance) {
            if (empty($attendance->company_id) && !empty($employee->company_id)) {
                $payload['company_id'] = $employee->company_id;
            }
            $attendance->update($payload);
        } else {
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'company_id' => $employee->company_id,
                ...$payload,
                'status' => $payload['status'] ?? 'present',
            ]);
        }

        return response()->json($attendance->id);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $request->attributes->get('employee');
        $employee->loadMissing('department');
        if ((int) $attendance->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'date' => ['sometimes', 'date'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date'],
            'lat_in' => ['nullable', 'numeric'],
            'lng_in' => ['nullable', 'numeric'],
            'lat_out' => ['nullable', 'numeric'],
            'lng_out' => ['nullable', 'numeric'],
            'status' => ['sometimes', 'in:present,late,absent,permit,leave'],
            'photo_in_path' => ['nullable', 'string'],
            'photo_out_path' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'notes_in' => ['nullable', 'string'],
            'notes_out' => ['nullable', 'string'],
            'photo' => ['nullable', 'file', 'max:10240'],
            // Compatibility fields used by legacy/manual clients.
            'type' => ['nullable', 'in:in,out'],
            'datetime' => ['nullable', 'date'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'note' => ['nullable', 'string'],
            'attachment' => ['nullable', 'string'],
        ]);

        $this->applyLegacyFieldMapping($request, $validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendances', 'public');
            $type = $validated['type'] ?? null;
            if ($type === 'out' || !empty($validated['check_out'])) {
                $validated['photo_out_path'] = $path;
            } elseif ($type === 'in' || !empty($validated['check_in'])) {
                $validated['photo_in_path'] = $path;
            }
        }

        $payload = $this->nonNullPayload($validated);
        $this->applyAttendanceMetrics($payload, $employee, $attendance);

        $attendance->update($payload);

        return response()->json(['success' => true]);
    }

    private function transform(Attendance $attendance): array
    {
        return [
            'id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'date' => $attendance->date?->format('Y-m-d'),
            'check_in' => $attendance->check_in?->format('Y-m-d H:i:s'),
            'check_out' => $attendance->check_out?->format('Y-m-d H:i:s'),
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
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
        ];
    }

    private function nonNullPayload(array $validated): array
    {
        return array_filter(
            $validated,
            static fn ($value) => $value !== null
        );
    }

    private function applyLegacyFieldMapping(Request $request, array &$validated): void
    {
        $type = $validated['type'] ?? $request->input('type');
        $datetime = $validated['datetime'] ?? $request->input('datetime');

        if (!empty($datetime)) {
            if ($type === 'out') {
                $validated['check_out'] = $validated['check_out'] ?? $datetime;
            } else {
                $validated['check_in'] = $validated['check_in'] ?? $datetime;
            }
        }

        if ($request->filled('latitude') || $request->filled('longitude')) {
            $latitude = $validated['latitude'] ?? $request->input('latitude');
            $longitude = $validated['longitude'] ?? $request->input('longitude');

            if ($type === 'out') {
                $validated['lat_out'] = $validated['lat_out'] ?? $latitude;
                $validated['lng_out'] = $validated['lng_out'] ?? $longitude;
            } else {
                $validated['lat_in'] = $validated['lat_in'] ?? $latitude;
                $validated['lng_in'] = $validated['lng_in'] ?? $longitude;
            }
        }

        if ($request->filled('note')) {
            $legacyNote = $request->input('note');
            if ($type === 'out') {
                $validated['notes_out'] = $validated['notes_out'] ?? $legacyNote;
            } else {
                $validated['notes_in'] = $validated['notes_in'] ?? $legacyNote;
            }

            // Keep old single notes field for backward compatibility with existing screens.
            $validated['notes'] = $validated['notes'] ?? $legacyNote;
        }

        if ($request->filled('attachment')) {
            $attachmentPath = $this->normalizeAttachmentPath((string) $request->input('attachment'));
            if ($type === 'out') {
                $validated['photo_out_path'] = $validated['photo_out_path'] ?? $attachmentPath;
            } else {
                $validated['photo_in_path'] = $validated['photo_in_path'] ?? $attachmentPath;
            }
        }
    }

    private function normalizeAttachmentPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return $path;
        }

        // Convert full URL /storage/... into relative storage path.
        if (Str::contains($path, '/storage/')) {
            return Str::after($path, '/storage/');
        }

        return ltrim($path, '/');
    }

    private function applyAttendanceMetrics(array &$payload, Employee $employee, ?Attendance $existingAttendance = null): void
    {
        $effectiveCheckIn = $payload['check_in'] ?? $existingAttendance?->check_in;
        $effectiveCheckOut = $payload['check_out'] ?? $existingAttendance?->check_out;
        $lateMinutes = $this->calculateLateMinutes($effectiveCheckIn, $employee);
        if ($lateMinutes !== null) {
            $payload['late_minutes'] = $lateMinutes;
        }

        $earlyMinutes = $this->calculateEarlyDepartureMinutes($effectiveCheckOut, $employee);
        if ($earlyMinutes !== null) {
            $payload['early_departure_minutes'] = $earlyMinutes;
        }

        $manualStatus = $payload['status'] ?? null;
        if (in_array($manualStatus, ['permit', 'leave', 'absent'], true)) {
            return;
        }

        if ($effectiveCheckIn || $effectiveCheckOut) {
            $payload['status'] = max(0, (int) ($lateMinutes ?? 0)) > 0 ? 'late' : 'present';
        }
    }

    private function calculateLateMinutes(mixed $checkIn, Employee $employee): ?int
    {
        if (!$checkIn) {
            return null;
        }

        $workStartTime = $employee->department?->work_start_time ?: '08:00:00';
        $startMinutes = $this->parseDepartmentClockMinutes($workStartTime, 8 * 60);
        $checkInMinutes = $this->extractClockMinutes($checkIn);
        if ($checkInMinutes === null) {
            return null;
        }

        return max(0, $checkInMinutes - $startMinutes);
    }

    private function calculateEarlyDepartureMinutes(mixed $checkOut, Employee $employee): ?int
    {
        if (!$checkOut) {
            return null;
        }

        $workEndTime = $employee->department?->work_end_time ?: '17:00:00';
        $endMinutes = $this->parseDepartmentClockMinutes($workEndTime, 17 * 60);
        $checkOutMinutes = $this->extractClockMinutes($checkOut);
        if ($checkOutMinutes === null) {
            return null;
        }

        return max(0, $endMinutes - $checkOutMinutes);
    }

    private function parseClockMinutes(mixed $value, int $fallbackMinutes): int
    {
        if (!is_string($value) || trim($value) === '') {
            return $fallbackMinutes;
        }

        $raw = trim($value);
        foreach (['H:i:s', 'H:i', 'h:i:s A', 'h:i A'] as $format) {
            try {
                $time = Carbon::createFromFormat($format, $raw, self::ATTENDANCE_TIMEZONE);
                return ((int) $time->format('H')) * 60 + (int) $time->format('i');
            } catch (\Throwable $_) {
                // Try next format.
            }
        }

        return $fallbackMinutes;
    }

    private function parseDepartmentClockMinutes(mixed $value, int $fallbackMinutes): int
    {
        $rawMinutes = $this->parseClockMinutes($value, $fallbackMinutes);

        // Department time columns can be saved in UTC-clock while business logic is WIB.
        // Normalize by hour range so Nuxi/API attendance computes consistently:
        // - Start time before 04:00 -> treat as UTC clock and shift +7h.
        // - End time before 13:00 -> treat as UTC clock and shift +7h.
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
            } catch (\Throwable $_) {
                return null;
            }
        }

        return null;
    }
}
