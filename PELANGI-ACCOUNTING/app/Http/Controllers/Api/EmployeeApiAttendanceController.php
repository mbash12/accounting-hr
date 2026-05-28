<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSpot;
use App\Models\Employee;
use App\Services\AttendanceInputService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeApiAttendanceController extends Controller
{
    private const ATTENDANCE_TIMEZONE = 'Asia/Jakarta';

    public function __construct(
        private readonly AttendanceInputService $attendanceInput,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $page = max((int) $request->query('page', 1), 1);
        $perPage = 10;

        $query = Attendance::query()
            ->with('clocks')
            ->where('employee_id', $employee->id)
            ->latest('id');

        $results = (clone $query)->count();
        $records = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (Attendance $attendance) => $this->attendanceInput->transformAttendance($attendance))
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

        $attendance->load('clocks');

        return response()->json($this->attendanceInput->transformAttendance($attendance));
    }

    public function store(Request $request): JsonResponse
    {
        return $this->recordTap($request);
    }

    public function clock(Request $request): JsonResponse
    {
        return $this->recordTap($request);
    }

    private function recordTap(Request $request): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $request->attributes->get('employee');
        $employee->loadMissing('department');

        $validated = $this->validatePayload($request, true);
        $this->validateAttendanceLocation($validated, $employee, null);

        $photoPath = $this->storeUploadedPhoto($request, $validated);

        try {
            $attendance = $this->attendanceInput->recordEmployeePayload($employee, $validated, $photoPath);
        } catch (ValidationException $e) {
            $photoError = $e->errors()['photo'][0] ?? 'Wajah tidak dikenali.';
            return response()->json([
                'message' => $photoError,
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json([
            'id' => $attendance->id,
            'attendance' => $this->attendanceInput->transformAttendance($attendance),
        ]);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $request->attributes->get('employee');
        $employee->loadMissing('department');

        if ((int) $attendance->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $this->validatePayload($request, false);
        $validated['date'] = $validated['date'] ?? $attendance->date->format('Y-m-d');

        $this->validateAttendanceLocation($validated, $employee, $attendance);

        $photoPath = $this->storeUploadedPhoto($request, $validated);

        $attendance = $this->attendanceInput->recordEmployeePayload($employee, $validated, $photoPath);

        return response()->json([
            'id' => $attendance->id,
            'attendance' => $this->attendanceInput->transformAttendance($attendance),
        ]);
    }

    private function validatePayload(Request $request, bool $requireDateOrDatetime): array
    {
        $validated = $request->validate([
            'date' => [$requireDateOrDatetime ? 'required_without:datetime' : 'sometimes', 'date'],
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

        return $validated;
    }

    private function storeUploadedPhoto(Request $request, array &$validated): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $path = $request->file('photo')->store('attendances', 'public');
        $type = $validated['type'] ?? null;

        if ($type === 'out' || ! empty($validated['check_out'])) {
            $validated['photo_out_path'] = $path;
        } else {
            $validated['photo_in_path'] = $path;
        }

        return $path;
    }

    // ---------------------------------------------------------------
    // Legacy field mapping
    // ---------------------------------------------------------------

    private function applyLegacyFieldMapping(Request $request, array &$validated): void
    {
        $type = $validated['type'] ?? $request->input('type');
        $datetime = $validated['datetime'] ?? $request->input('datetime');

        if (! empty($datetime)) {
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

        if (Str::contains($path, '/storage/')) {
            return Str::after($path, '/storage/');
        }

        return ltrim($path, '/');
    }

    // ---------------------------------------------------------------
    // Location validation
    // ---------------------------------------------------------------

    private function validateAttendanceLocation(array $validated, Employee $employee, ?Attendance $existingAttendance): void
    {
        $spots = AttendanceSpot::query()
            ->where('company_id', $employee->company_id)
            ->where('is_active', true)
            ->get(['id', 'name', 'latitude', 'longitude', 'radius_meters']);

        if ($spots->isEmpty()) {
            return;
        }

        $this->validateCoordinateWithinRadius(
            $validated,
            'check_in',
            'lat_in',
            'lng_in',
            $existingAttendance,
            $spots
        );

        $this->validateCoordinateWithinRadius(
            $validated,
            'check_out',
            'lat_out',
            'lng_out',
            $existingAttendance,
            $spots
        );
    }

    private function validateCoordinateWithinRadius(
        array $validated,
        string $timeField,
        string $latField,
        string $lngField,
        ?Attendance $existingAttendance,
        \Illuminate\Support\Collection $spots
    ): void {
        $isTryingToSubmitTime = array_key_exists($timeField, $validated);
        $isTryingToSubmitCoordinate = array_key_exists($latField, $validated) || array_key_exists($lngField, $validated);

        if (! $isTryingToSubmitTime && ! $isTryingToSubmitCoordinate) {
            return;
        }

        $latitude = $validated[$latField] ?? $existingAttendance?->{$latField};
        $longitude = $validated[$lngField] ?? $existingAttendance?->{$lngField};

        if (! is_numeric($latitude) || ! is_numeric($longitude)) {
            throw ValidationException::withMessages([
                $latField => 'Attendance coordinates are required.',
            ]);
        }

        $bestMatch = $spots
            ->map(function (AttendanceSpot $spot) use ($latitude, $longitude) {
                $distanceMeters = $this->calculateDistanceMeters(
                    (float) $latitude,
                    (float) $longitude,
                    (float) $spot->latitude,
                    (float) $spot->longitude
                );

                return [
                    'spot' => $spot,
                    'distance' => $distanceMeters,
                    'inside' => $distanceMeters <= (float) $spot->radius_meters,
                ];
            })
            ->sortBy('distance')
            ->first();

        if (! $bestMatch || ! $bestMatch['inside']) {
            $nearestDistance = $bestMatch['distance'] ?? 0;
            $nearestSpotName = $bestMatch['spot']->name ?? '-';
            $nearestRadius = $bestMatch['spot']->radius_meters ?? 0;
            throw ValidationException::withMessages([
                $latField => sprintf(
                    'Attendance location is outside all allowed spots. Nearest spot: %s (radius %.0f m, distance %.0f m).',
                    $nearestSpotName,
                    (float) $nearestRadius,
                    (float) $nearestDistance
                ),
            ]);
        }
    }

    private function calculateDistanceMeters(
        float $latitudeA,
        float $longitudeA,
        float $latitudeB,
        float $longitudeB
    ): float {
        $earthRadiusMeters = 6371000;
        $latARad = deg2rad($latitudeA);
        $latBRad = deg2rad($latitudeB);
        $deltaLat = deg2rad($latitudeB - $latitudeA);
        $deltaLng = deg2rad($longitudeB - $longitudeA);

        $hav = sin($deltaLat / 2) ** 2
            + cos($latARad) * cos($latBRad) * sin($deltaLng / 2) ** 2;
        $arc = 2 * atan2(sqrt($hav), sqrt(1 - $hav));

        return $earthRadiusMeters * $arc;
    }
}
