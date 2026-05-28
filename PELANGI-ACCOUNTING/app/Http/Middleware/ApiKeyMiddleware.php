<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('BIOMETRIC_API_KEY', 'GDb5Yd5P2t2qEXj5jx4R6XEy');
        $apiKey = $request->header('X-API-KEY');

        Log::channel('biometric')->info('Biometric API request', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
            'payload' => $request->except(['photo', 'image']),
        ]);

        if ($apiKey !== $expectedKey) {
            Log::channel('biometric')->warning('Biometric API unauthorized', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
