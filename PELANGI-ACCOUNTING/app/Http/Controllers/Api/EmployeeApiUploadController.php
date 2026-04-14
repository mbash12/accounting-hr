<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeApiUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('employeeapi/uploads', 'public');

        return response()->json([
            // Return path relative to public disk, compatible with Filament FileUpload.
            'path' => $path,
        ]);
    }
}
