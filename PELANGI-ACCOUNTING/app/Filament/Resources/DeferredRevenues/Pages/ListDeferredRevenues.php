<?php

namespace App\Filament\Resources\DeferredRevenues\Pages;

use App\Filament\Resources\DeferredRevenues\DeferredRevenueResource;
use App\Services\DeferredRevenueService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDeferredRevenues extends ListRecords
{
    protected static string $resource = DeferredRevenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('recognizeDue')
                ->label(__('Recognize Due'))
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('Recognize All Due Revenues'))
                ->modalDescription(__('This will create journal entries for all schedule lines whose period has ended and are still pending.'))
                ->action(function () {
                    $service = app(DeferredRevenueService::class);
                    $companyId = session('selected_company_id');
                    $count = $service->recognizeDue($companyId ? (int) $companyId : null);

                    Notification::make()
                        ->title(__('Recognition complete'))
                        ->body("{$count} schedule(s) recognized.")
                        ->success()
                        ->send();

                    $this->resetTable();
                }),
        ];
    }
}
