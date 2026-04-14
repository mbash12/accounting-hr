<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EmployeeApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $employeeId = Cache::get($this->tokenCacheKey($token));
        if (! $employeeId) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $employee = Employee::query()
            ->whereKey($employeeId)
            ->where('is_active', true)
            ->first();

        if (! $employee) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->attributes->set('employee', $employee);
        $request->attributes->set('employeeapi_token', $token);

        return $next($request);
    }

    private function tokenCacheKey(string $token): string
    {
        return "employeeapi_auth_token:{$token}";
    }
}
