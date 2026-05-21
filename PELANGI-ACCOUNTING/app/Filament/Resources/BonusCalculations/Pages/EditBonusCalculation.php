<?php

namespace App\Filament\Resources\BonusCalculations\Pages;

use App\Filament\Resources\BonusCalculations\BonusCalculationResource;
use App\Services\PayrollService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBonusCalculation extends EditRecord
{
    protected static string $resource = BonusCalculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calculateBonus')
                ->label(__('Hitung Pajak Bonus'))
                ->icon('heroicon-o-cpu-chip')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status !== 'posted')
                ->action(function (PayrollService $service) {
                    try {
                        $this->save();
                        $service->calculateBonusForPeriod($this->record);
                        
                        $this->refreshFormData(['status', 'total_amount', 'total_pph21']);
                        
                        Notification::make()
                            ->title(__('Pajak bonus berhasil dihitung'))
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Failed to calculate bonus'))
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            Action::make('postToLedger')
                ->label(__('Posting ke Jurnal'))
                ->icon('heroicon-o-book-open')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'processed')
                ->color('success')
                ->action(function (PayrollService $service) {
                    try {
                        $service->postBonusToLedger($this->record);
                        
                        $this->refreshFormData(['status']);
                        
                        Notification::make()
                            ->title(__('Bonus berhasil diposting ke jurnal'))
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
