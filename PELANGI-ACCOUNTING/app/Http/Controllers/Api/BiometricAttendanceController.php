<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceInputService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BiometricAttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceInputService $attendanceInput,
    ) {}

    /**
     * Batch push clock events from the biometric machine via local broker.
     *
     * Accepts array of:
     *   { employee_id, date?, clock_in?, clock_out?, type?, datetime? }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            '*.employee_id' => ['required', 'integer'],
            '*.company_id' => ['nullable', 'integer'],
            '*.date' => ['nullable', 'date_format:Y-m-d'],
            '*.clock_in' => ['nullable', 'date_format:Y-m-d H:i:s'],
            '*.clock_out' => ['nullable', 'date_format:Y-m-d H:i:s'],
            '*.type' => ['nullable', 'in:in,out'],
            '*.datetime' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ]);

        $result = $this->attendanceInput->recordBiometricBatch($validated);

        return response()->json([
            'records' => $result['processed'],
            'skipped' => $result['skipped'],
        ]);
    }

    /**
     * Acknowledge log clear from the broker.
     */
    public function clearLog(): JsonResponse
    {
        return response()->json(['success' => true]);
    }
}
