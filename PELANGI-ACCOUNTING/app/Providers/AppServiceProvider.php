<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use App\Models\Employee;
use App\Models\OvertimeLog;
use App\Models\Permit;
use App\Observers\EmployeeObserver;
use App\Observers\OvertimeLogObserver;
use App\Observers\PermitObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-calculate overtime allowance on approval
        OvertimeLog::observe(OvertimeLogObserver::class);

        // Auto-update leave quota when permit is approved/rejected
        Permit::observe(PermitObserver::class);

        // Auto-create leave quota for new employees
        Employee::observe(EmployeeObserver::class);
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => '<style>
                .fi-main {
                    max-width: none !important;
                }
                .fi-width-7xl {
                    max-width: none !important;
                    width: 100% !important;
                }
            </style>'
        );
    }
}
