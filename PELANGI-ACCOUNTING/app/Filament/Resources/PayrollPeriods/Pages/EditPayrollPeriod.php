<?php

namespace App\Filament\Resources\PayrollPeriods\Pages;

use App\Filament\Resources\PayrollPeriods\PayrollPeriodResource;
use App\Models\PayrollPeriod;
use App\Services\PayrollService;
use App\Traits\WarnEditPostedRecord;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPayrollPeriod extends EditRecord
{
    use WarnEditPostedRecord;

    protected static string $resource = PayrollPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generatePayslips')
                ->label(__('Generate Payslip'))
                ->icon('heroicon-o-cpu-chip')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading(__('Generate Payslip'))
                ->modalDescription(__('This will delete existing payslips (if any) and regenerate them for all active employees. Continue?'))
                ->visible(fn (): bool => in_array($this->record->status, ['draft', 'processed']))
                ->action(function (PayrollService $service) {
                    /** @var PayrollPeriod $period */
                    $period = $this->record;
                    $service->generatePayslips($period);
                    Notification::make()
                        ->title(__('Payslip generated successfully'))
                        ->success()
                        ->send();
                    $this->redirect(
                        PayrollPeriodResource::getUrl('edit', ['record' => $period]),
                    );
                }),
            Action::make('downloadPayslips')
                ->label(__('Download Payslip'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn (): bool => $this->record->payslips()->exists())
                ->url(fn (): string => route('payslip.pdf.period', $this->record->id))
                ->openUrlInNewTab(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
