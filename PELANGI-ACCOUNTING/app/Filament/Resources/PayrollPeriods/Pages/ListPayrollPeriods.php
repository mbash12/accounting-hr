<?php

namespace App\Filament\Resources\PayrollPeriods\Pages;

use App\Filament\Resources\PayrollPeriods\PayrollPeriodResource;
use App\Services\PayrollService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayrollPeriods extends ListRecords
{
    protected static string $resource = PayrollPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function generatePayslips(PayrollPeriodResource $resource): Action
    {
        return Action::make('generatePayslips')
            ->label('Generate Payslips')
            ->requiresConfirmation()
            ->action(function (array $data, PayrollService $service) {
                // This would normally be used on a per-record basis.
                // For ListRecords, we might want to use a Table Action instead.
            });
    }
}
