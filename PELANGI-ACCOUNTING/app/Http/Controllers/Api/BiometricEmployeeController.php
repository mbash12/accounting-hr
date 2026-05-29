<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiometricEmployee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BiometricEmployeeController extends Controller
{
    /**
     * List active employees for the biometric machine to download.
     */
    public function index(): JsonResponse
    {
        $records = BiometricEmployee::query()
            ->where('is_active', true)
            ->get()
            ->map(fn (BiometricEmployee $be) => [
                'id' => $be->machine_user_id,
                'name' => $be->name,
            ]);

        return response()->json(['records' => $records]);
    }

    /**
     * Sync employee list from the biometric machine / broker.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            '*.id' => ['required', 'integer'],
            '*.name' => ['required', 'string'],
            '*.company_id' => ['nullable', 'integer'],
        ]);

        foreach ($validated as $item) {
            BiometricEmployee::updateOrCreate(
                ['machine_user_id' => $item['id']],
                ['name' => $item['name'], 'company_id' => $item['company_id'] ?? null, 'is_active' => true]
            );
        }

        return response()->json(['records' => count($validated)]);
    }

    /**
     * Clear all biometric employee records (called after full re-sync).
     */
    public function clear(): JsonResponse
    {
        BiometricEmployee::query()->delete();

        return response()->json(['success' => true]);
    }
}
