<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use App\Models\Employee;
use App\Models\OvertimeLog;
use App\Models\Permit;
use App\Models\ShiftType;
use App\Observers\EmployeeObserver;
use App\Observers\OvertimeLogObserver;
use App\Observers\PermitObserver;
use App\Policies\ShiftTypePolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Observers
        OvertimeLog::observe(OvertimeLogObserver::class);
        Permit::observe(PermitObserver::class);
        Employee::observe(EmployeeObserver::class);

        // Shield-generated policies
        Gate::policy(ShiftType::class,                ShiftTypePolicy::class);

        // Global CSS reset for Filament panel so our app CSS can take over
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => '<link rel="stylesheet" href="' . asset('css/app.css') . (file_exists(public_path('css/app.css')) ? '?v=' . filemtime(public_path('css/app.css')) : '') . '">'
                . '<style>.fi-main { max-width: none !important; }</style>'
        );
    }
}
