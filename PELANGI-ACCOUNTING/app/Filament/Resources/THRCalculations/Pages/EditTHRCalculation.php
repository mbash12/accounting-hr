<?php

namespace App\Filament\Resources\THRCalculations\Pages;

use App\Filament\Resources\THRCalculations\THRCalculationResource;
use App\Services\PayrollService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTHRCalculation extends EditRecord
{
    protected static string $resource = THRCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calculateTHR')
                ->label(__('Calculate THR'))
                ->icon('heroicon-o-cpu-chip')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status !== 'posted')
                ->action(function (PayrollService $service) {
                    try {
                        $this->save();
                        $service->calculateTHRForPeriod($this->record);
                        
                        $this->refreshFormData(['status', 'total_amount', 'total_pph21']);
                        
                        Notification::make()
                            ->title(__('THR calculated successfully'))
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Failed to calculate THR'))
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            Action::make('postToLedger')
                ->label(__('Post to Journal'))
                ->icon('heroicon-o-book-open')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'processed')
                ->color('success')
                ->action(function (PayrollService $service) {
                    try {
                        $service->postTHRToLedger($this->record);
                        
                        $this->refreshFormData(['status']);
                        
                        Notification::make()
                            ->title(__('THR posted to journal successfully'))
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Failed to post to journal'))
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
