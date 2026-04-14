<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeApiPermitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $status = $request->query('status');
        $page = max((int) $request->query('page', 1), 1);
        $perPage = 20;

        $query = Permit::query()
            ->where('employee_id', $employee->id)
            ->latest('id');

        if ($status) {
            $query->where('status', $status);
        }

        $results = (clone $query)->count();
        $records = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (Permit $permit) => $this->transform($permit))
            ->values();

        return response()->json([
            'records' => $records,
            'results' => $results,
        ]);
    }

    public function show(Request $request, Permit $permit): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        if ((int) $permit->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($this->transform($permit));
    }

    public function store(Request $request): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        $validated = $request->validate([
            'type' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'reason' => ['nullable', 'string'],
            'attachment_path' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);

        $permit = Permit::create([
            'employee_id' => $employee->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'attachment_path' => $validated['attachment_path'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        return response()->json($permit->id);
    }

    public function update(Request $request, Permit $permit): JsonResponse
    {
        $employee = $request->attributes->get('employee');
        if ((int) $permit->employee_id !== (int) $employee->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'type' => ['sometimes', 'string'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date'],
            'reason' => ['nullable', 'string'],
            'attachment_path' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,approved,rejected'],
        ]);

        $permit->update($validated);

        return response()->json(['success' => true]);
    }

    private function transform(Permit $permit): array
    {
        return [
            'id' => $permit->id,
            'employee_id' => $permit->employee_id,
            'type' => $permit->type,
            'start_date' => $permit->start_date?->format('Y-m-d'),
            'end_date' => $permit->end_date?->format('Y-m-d'),
            'start' => $permit->start_date?->format('Y-m-d 00:00:00'),
            'end' => $permit->end_date?->format('Y-m-d 00:00:00'),
            'reason' => $permit->reason,
            'duration' => $permit->start_date && $permit->end_date
                ? $permit->start_date->diffInDays($permit->end_date) + 1
                : 1,
            'status' => $permit->status,
            'attachment_path' => $permit->attachment_path,
            'created_at' => $permit->created_at,
            'updated_at' => $permit->updated_at,
        ];
    }
}
