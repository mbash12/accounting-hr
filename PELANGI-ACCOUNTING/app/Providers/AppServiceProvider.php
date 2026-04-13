<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use App\Models\SalesInvoice;
use App\Observers\SalesInvoiceObserver;

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
        // Register observer for back-sync to Inventory
        SalesInvoice::observe(SalesInvoiceObserver::class);
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
