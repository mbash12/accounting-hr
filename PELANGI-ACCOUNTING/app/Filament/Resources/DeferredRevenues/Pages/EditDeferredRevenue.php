<?php

namespace App\Filament\Resources\DeferredRevenues\Pages;

use App\Filament\Resources\DeferredRevenues\DeferredRevenueResource;
use App\Models\DeferredRevenue;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDeferredRevenue extends EditRecord
{
    protected static string $resource = DeferredRevenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('activate')
                ->label(__('Activate'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('Activate Contract'))
                ->modalDescription(__('Once activated, this contract is ready for revenue recognition.'))
                ->action(function (DeferredRevenue $record) {
                    if ($record->status !== 'draft') {
                        Notification::make()->title(__('Only draft contracts can be activated.'))->warning()->send();
                        return;
                    }
                    $record->update(['status' => 'active']);
                    Notification::make()->title(__('Contract activated.'))->success()->send();
                })
                ->visible(fn (DeferredRevenue $record) => $record->status === 'draft'),

            Action::make('cancel')
                ->label(__('Cancel'))
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('Cancel Contract'))
                ->modalDescription(__('Cancel this contract? Recognized schedules will not be affected.'))
                ->action(function (DeferredRevenue $record) {
                    if (!in_array($record->status, ['draft', 'active'])) {
                        Notification::make()->title(__('Only draft or active contracts can be cancelled.'))->warning()->send();
                        return;
                    }
                    $record->update(['status' => 'cancelled']);
                    Notification::make()->title(__('Contract cancelled.'))->success()->send();
                })
                ->visible(fn (DeferredRevenue $record) => in_array($record->status, ['draft', 'active'])),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
