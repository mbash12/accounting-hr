<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas\Pages;

use App\Filament\Resources\EmployeeLeaveQuotas\EmployeeLeaveQuotaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeLeaveQuotas extends ListRecords
{
    protected static string $resource = EmployeeLeaveQuotaResource::class;

    public function getTitle(): string
    {
        return __('Daftar Kuota Cuti');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
