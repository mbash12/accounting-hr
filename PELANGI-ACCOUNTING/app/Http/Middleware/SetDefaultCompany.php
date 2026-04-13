<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply for authenticated users in Filament
        if (Auth::check() && str_starts_with($request->path(), 'main')) {
            $user = Auth::user();

            // Only set default if user has companies and no company is selected
            if ($user->companies()->count() > 0 && !session('selected_company_id')) {
                // Set to user's first assigned company
                $firstCompany = $user->companies()->first();
                if ($firstCompany) {
                    session(['selected_company_id' => $firstCompany->id]);
                }
            }
        }

        return $next($request);
    }
}
