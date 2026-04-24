<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSpot;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeApiAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $employee = Employee::query()
            ->where('email', $validated['email'])
            ->where('is_active', true)
            ->first();

        if (! $employee || ! $employee->password || ! Hash::check($validated['password'], $employee->password)) {
            return response()->json([
                'message' => 'Email atau password tidak valid.',
            ], 401);
        }

        $token = Str::random(64);
        Cache::put($this->tokenCacheKey($token), $employee->id, now()->addDays(7));

        return response()->json([
            'token' => $token,
            'employee' => $this->transformEmployee($employee),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var Employee|null $employee */
        $employee = $request->attributes->get('employee');

        if (! $employee) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'employee' => $this->transformEmployee($employee),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->attributes->get('employeeapi_token');
        if (is_string($token) && $token !== '') {
            Cache::forget($this->tokenCacheKey($token));
        }

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    private function tokenCacheKey(string $token): string
    {
        return "employeeapi_auth_token:{$token}";
    }

    private function transformEmployee(Employee $employee): array
    {
        $employee->loadMissing('department', 'company');
        $attendanceSpots = AttendanceSpot::query()
            ->where('company_id', $employee->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'latitude', 'longitude', 'radius_meters'])
            ->map(static fn (AttendanceSpot $spot) => [
                'id' => $spot->id,
                'name' => $spot->name,
                'latitude' => (float) $spot->latitude,
                'longitude' => (float) $spot->longitude,
                'radius_meters' => (int) $spot->radius_meters,
            ])
            ->values()
            ->all();

        return [
            'id' => $employee->id,
            'employee_id' => $employee->employee_id,
            'name' => $employee->name,
            'fullname' => $employee->name,
            'email' => $employee->email,
            'nik' => $employee->nik,
            'position' => $employee->position,
            'status' => $employee->status,
            'join_date' => optional($employee->hire_date)?->format('Y-m-d'),
            'hire_date' => optional($employee->hire_date)?->format('Y-m-d'),
            'role' => 'staff',
            'department_id' => $employee->department,
            'is_active' => $employee->is_active,
            'attendance_spots' => $attendanceSpots,
        ];
    }
}
