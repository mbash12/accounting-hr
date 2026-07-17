<?php

namespace App\Traits;

use App\Services\PeriodClosingService;

trait EnforcesOpenPeriod
{
    protected static function bootEnforcesOpenPeriod(): void
    {
        static::saving(function ($model) {
            static::assertModelPeriodOpen($model);
        });

        static::deleting(function ($model) {
            static::assertModelPeriodOpen($model);
        });
    }

    protected static function assertModelPeriodOpen($model): void
    {
        $companyId = $model->company_id ?? null;
        if (!$companyId) {
            return;
        }

        // Closing JE itself is managed by PeriodClosingService.
        if (($model->sub_module ?? null) === 'period_closing') {
            return;
        }

        $date = $model->date
            ?? $model->invoice_date
            ?? $model->transaction_date
            ?? $model->payment_date
            ?? null;

        if (!$date) {
            return;
        }

        app(PeriodClosingService::class)->assertOpen((int) $companyId, $date);
    }
}
