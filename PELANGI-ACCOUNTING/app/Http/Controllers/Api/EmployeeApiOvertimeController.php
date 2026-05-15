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
            'hours' => ['required', 'numeric', 'min:0.5'],
            'is_holiday' => ['boolean'],
            'reason' => ['nullable', 'string'],
        ]);

        $overtimeLog = OvertimeLog::create([
            'employee_id' => $employee->id,
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'is_holiday' => $validated['is_holiday'] ?? false,
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
            'hours' => ['sometimes', 'numeric', 'min:0.5'],
            'is_holiday' => ['sometimes', 'boolean'],
            'reason' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:draft,approved,rejected,cancelled'],
        ]);

        $overtimeLog->update($validated);

        return response()->json(['success' => true]);
    }

    private function transform(OvertimeLog $log): array
    {
        return [
            'id' => $log->id,
            'employee_id' => $log->employee_id,
            'date' => $log->date?->format('Y-m-d'),
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
