<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas\Pages;

use App\Filament\Resources\EmployeeLeaveQuotas\EmployeeLeaveQuotaResource;
use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeLeaveQuotas extends ListRecords
{
    protected static string $resource = EmployeeLeaveQuotaResource::class;

    public function getTitle(): string
    {
        return __('Leave Quota List');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label(__('Generate / Sync'))
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->form([
                    Select::make('year')
                        ->label(__('Year'))
                        ->options(fn () => [
                            now()->year => now()->year,
                            now()->year + 1 => now()->year + 1,
                        ])
                        ->default(now()->year)
                        ->required(),
                    TextInput::make('default_quota')
                        ->label(__('Default Quota (Days)'))
                        ->numeric()
                        ->default(12)
                        ->required(),
                ])
                ->modalHeading(__('Generate Leave Quotas'))
                ->modalDescription(__('Create leave quota records for all active employees that do not already have one for the selected year.'))
                ->modalSubmitActionLabel(__('Generate'))
                ->action(function (array $data) {
                    $year = (int) $data['year'];
                    $defaultQuota = (int) $data['default_quota'];
                    $companyId = session('selected_company_id');

                    $query = Employee::where('is_active', true);
                    if ($companyId) {
                        $query->where('company_id', $companyId);
                    }

                    $employees = $query->get();
                    $created = 0;
                    $skipped = 0;

                    foreach ($employees as $employee) {
                        $exists = EmployeeLeaveQuota::where('employee_id', $employee->id)
                            ->where('year', $year)
                            ->exists();

                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        EmployeeLeaveQuota::create([
                            'employee_id' => $employee->id,
                            'year' => $year,
                            'total_quota' => $defaultQuota,
                            'used_quota' => 0,
                            'remaining_quota' => $defaultQuota,
                            'company_id' => $employee->company_id,
                        ]);
                        $created++;
                    }

                    Notification::make()
                        ->title(__('Generation complete'))
                        ->body(__("Created: {$created}, Skipped (already exists): {$skipped}, Total employees: {$employees->count()}"))
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
