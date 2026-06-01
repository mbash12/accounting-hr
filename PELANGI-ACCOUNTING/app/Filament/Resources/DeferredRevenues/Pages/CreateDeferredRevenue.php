<?php

namespace App\Filament\Resources\DeferredRevenues\Pages;

use App\Filament\Resources\DeferredRevenues\DeferredRevenueResource;
use App\Services\DeferredRevenueService;
use Filament\Resources\Pages\CreateRecord;

class CreateDeferredRevenue extends CreateRecord
{
    protected static string $resource = DeferredRevenueResource::class;

    protected function afterCreate(): void
    {
        // Auto-generate the amortization schedule after creation
        $service = app(DeferredRevenueService::class);
        $service->generateSchedule($this->record);
    }
}
