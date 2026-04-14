<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeApiAttendanceController extends Controller
{
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
        $employee = $request->attributes->get('employee');
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
            'photo' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendances', 'public');
            if (!empty($validated['check_in'])) {
                $validated['photo_in_path'] = $path;
            } elseif (!empty($validated['check_out'])) {
                $validated['photo_out_path'] = $path;
            } else {
                $validated['photo_in_path'] = $path;
            }
        }

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            ...$validated,
            'status' => $validated['status'] ?? 'present',
        ]);

        return response()->json($attendance->id);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $employee = $request->attributes->get('employee');
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
            'photo' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendances', 'public');
            if (!empty($validated['check_in'])) {
                $validated['photo_in_path'] = $path;
            } elseif (!empty($validated['check_out'])) {
                $validated['photo_out_path'] = $path;
            }
        }

        $attendance->update($validated);

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
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
        ];
    }
}
