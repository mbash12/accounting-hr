<?php

namespace App\Filament\Resources\CashDisbursements\Pages;

use App\Filament\Resources\CashDisbursements\CashDisbursementResource;
use App\Services\CashBankService;
use App\Services\CodeGeneratorService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateCashDisbursement extends CreateRecord
{
    protected static string $resource = CashDisbursementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => __('At least one item is required.'),
            ]);
        }

        foreach ($items as $index => $item) {
            if (empty($item['account_id'])) {
                throw ValidationException::withMessages([
                    "items.{$index}.account_id" => __('Account must be selected for each item.'),
                ]);
            }
        }

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

   
   
        $total = 0;
        foreach ($items as $item) {
            $itemTotal = $item['amount'] ?? 0;
            if (is_string($itemTotal)) {
                $itemTotal = str_replace(['.', ','], ['', '.'], $itemTotal);
            }
            $total += is_numeric($itemTotal) ? (float) $itemTotal : 0;
        }
        $data['total'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            if (empty($data['from_account_id'])) {
                throw ValidationException::withMessages([
                    'from_account_id' => __('Bank Account must be selected.'),
                ]);
            }

            $service = app(CashBankService::class);

            $defaultDepartment = \App\Models\Department::first();
            if (!$defaultDepartment) {
                throw ValidationException::withMessages([
                    'department_id' => __('Department not found. Please create a department first.'),
                ]);
            }

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
                        'company_id' => $data['company_id'] ?? 1,
                        'created_by_user_id' => $userId,
                    ]);
                } catch (\Exception $e) {
                    throw ValidationException::withMessages([
                        'cost_center_id' => __('Failed to create default cost center: ' . $e->getMessage()),
                    ]);
                }
            }

            $cleanedItems = [];
            foreach ($data['items'] ?? [] as $item) {
                $itemTotal = $item['amount'] ?? 0;
                if (is_string($itemTotal)) {
                    $itemTotal = str_replace(['.', ','], ['', '.'], $itemTotal);
                }
                $itemTotal = is_numeric($itemTotal) ? (float) $itemTotal : 0;
                
                $cleanedItems[] = [
                    'account_id' => $item['account_id'],
                    'amount' => $itemTotal,
                    'description' => $item['description'] ?? null,
                ];
            }

            try {
                $glAccountId = $data['from_account_id'];

                $status = 'draft';

                $disbursement = $service->createCashOut([
                    'date' => $data['date'],
                    'reference_no' => $data['reference_no'] ?? null,
                    'description' => $data['description'] ?? null,
                    'items' => $cleanedItems,
                    'from_account_id' => $glAccountId, 
                    'company_id' => $data['company_id'] ?? null,
                    'status' => $status,
                    'department_id' => $data['department_id'] ?? $defaultDepartment->id,
                    'cost_center_id' => $data['cost_center_id'] ?? $defaultCostCenter->id,
                ]);
                
                $this->record = $disbursement;
                
                return $disbursement;
            } catch (\InvalidArgumentException $e) {
                throw ValidationException::withMessages([
                    'form' => $e->getMessage(),
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating cash disbursement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            
            throw ValidationException::withMessages([
                'form' => __('An error occurred: ' . $e->getMessage()),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
