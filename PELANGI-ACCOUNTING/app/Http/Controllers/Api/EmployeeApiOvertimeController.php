<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OvertimeLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeApiOvertimeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $status = $request->query('status');
        $page = max((int) $request->query('page', 1), 1);
        $perPage = 20;

        $query = OvertimeLog::query()
            ->where('employee_id', $employee->id)
            ->latest('id');

        if ($status) {
            $query->where('status', $status);
        }

        $results = (clone $query)->count();
        $records = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (OvertimeLog $log) => $this->transform($log))
            ->values();

        return response()->json([
            'records' => $records,
            'results' => $results,
        ]);
    }

    public function show(Request $request, OvertimeLog $overtimeLog): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        if ((int) $overtimeLog->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($this->transform($overtimeLog));
    }

    public function store(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time_start' => ['nullable', 'date_format:H:i'],
            'time_end' => ['nullable', 'date_format:H:i'],
            'hours' => ['nullable', 'numeric', 'min:0.5'],
            'is_holiday' => ['boolean'],
            'reason' => ['nullable', 'string'],
        ]);

        // Auto-calculate hours from time_start/time_end if provided
        if (!isset($validated['hours']) && !empty($validated['time_start']) && !empty($validated['time_end'])) {
            $start = \Carbon\Carbon::parse($validated['time_start']);
            $end = \Carbon\Carbon::parse($validated['time_end']);
            $mins = abs($end->diffInMinutes($start));
            $validated['hours'] = max(0.5, round($mins / 60, 2));
        } elseif (!isset($validated['hours'])) {
            return response()->json(['message' => 'Either hours or time_start+time_end is required.'], 422);
        }

        // Always auto-detect from backend (authoritative: checks holidays table + department schedule + weekends).
        // Frontend value is ignored — department working_days and holiday table live in DB, not NUXI.
        $overtimeLog = OvertimeLog::create([
            'employee_id' => $employee->id,
            'date' => $validated['date'],
            'time_start' => $validated['time_start'] ?? null,
            'time_end' => $validated['time_end'] ?? null,
            'hours' => $validated['hours'],
            'is_holiday' => OvertimeLog::isHoliday($employee->id, $validated['date']),
            'reason' => $validated['reason'] ?? null,
            'status' => 'draft',
            'company_id' => $employee->company_id,
        ]);

        return response()->json($overtimeLog->id);
    }

    public function update(Request $request, OvertimeLog $overtimeLog): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        if ((int) $overtimeLog->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'date' => ['sometimes', 'date'],
            'time_start' => ['nullable', 'date_format:H:i'],
            'time_end' => ['nullable', 'date_format:H:i'],
            'hours' => ['sometimes', 'numeric', 'min:0.5'],
            'is_holiday' => ['sometimes', 'boolean'],
            'reason' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:draft,approved,rejected,cancelled'],
        ]);

        // Auto-calculate hours from time_start/time_end if provided
        if (!empty($validated['time_start']) && !empty($validated['time_end'])) {
            $start = \Carbon\Carbon::parse($validated['time_start']);
            $end = \Carbon\Carbon::parse($validated['time_end']);
            $mins = abs($end->diffInMinutes($start));
            $validated['hours'] = max(0.5, round($mins / 60, 2));
        }

        // If date changed, re-detect is_holiday
        if (isset($validated['date'])) {
            $validated['is_holiday'] = OvertimeLog::isHoliday($overtimeLog->employee_id, $validated['date']);
        }

        $overtimeLog->update($validated);

        return response()->json(['success' => true]);
    }

    private function transform(OvertimeLog $log): array
    {
        return [
            'id' => $log->id,
            'employee_id' => $log->employee_id,
            'date' => $log->date?->format('Y-m-d'),
            'time_start' => $log->time_start ? $log->time_start->format('H:i') : null,
            'time_end' => $log->time_end ? $log->time_end->format('H:i') : null,
            'hours' => (float) $log->hours,
            'is_holiday' => (bool) $log->is_holiday,
            'calculated_amount' => (float) $log->calculated_amount,
            'status' => $log->status,
            'reason' => $log->reason,
            'created_at' => $log->created_at,
            'updated_at' => $log->updated_at,
        ];
    }
}
