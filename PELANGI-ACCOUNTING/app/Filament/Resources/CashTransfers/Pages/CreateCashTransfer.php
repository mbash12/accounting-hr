<?php

namespace App\Filament\Resources\CashTransfers\Pages;

use App\Filament\Resources\CashTransfers\CashTransferResource;
use App\Services\CashBankService;
use App\Services\CodeGeneratorService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateCashTransfer extends CreateRecord
{
    protected static string $resource = CashTransferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(CashBankService::class);

        $defaultDepartment = \App\Models\Department::first();
        if (!$defaultDepartment) {
            throw ValidationException::withMessages([
                'department_id' => __('Department not found. Please create a department first.'),
            ]);
        }

        $companyId = $data['company_id'] ?? null;
        if (empty($companyId)) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $companyId = $selectedCompanyId;
            }
        }
        $data['company_id'] = $companyId;

        $defaultCostCenter = \App\Models\CostCenter::first();
        if (!$defaultCostCenter) {
            try {
                $userId = Auth::id();
                if (!$userId) {
                    throw ValidationException::withMessages([
                        'form' => __('User is not authenticated.'),
                    ]);
                }
                
                $defaultCostCenter = \App\Models\CostCenter::create([
                    'name' => 'Default',
                    'code' => 'DEFAULT',
                    'department_id' => $defaultDepartment->id,
                    'company_id' => $companyId ?? 1,
                    'created_by_user_id' => $userId,
                ]);
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'cost_center_id' => __('Failed to create default cost center: ' . $e->getMessage()),
                ]);
            }
        }

        $status = 'draft';

        try {
            return $service->createCashTransfer([
                'date' => $data['date'],
                'reference_no' => $data['reference_no'] ?? null,
                'transfer_number' => $data['transfer_number'] ?? null,
                'description' => $data['description'] ?? null,
                'amount' => $data['amount'],
                'from_account_id' => $data['from_account_id'],
                'to_account_id' => $data['to_account_id'],
                'company_id' => $data['company_id'] ?? null,
                'status' => $status,
                'department_id' => $data['department_id'] ?? $defaultDepartment->id,
                'cost_center_id' => $data['cost_center_id'] ?? $defaultCostCenter->id,
            ]);
        } catch (\InvalidArgumentException $e) {
            throw ValidationException::withMessages([
                'form' => $e->getMessage(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
