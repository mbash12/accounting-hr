<?php

namespace App\Filament\Resources\CashReceipts\Pages;

use App\Filament\Resources\CashReceipts\CashReceiptResource;
use App\Services\CashBankService;
use App\Services\CodeGeneratorService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateCashReceipt extends CreateRecord
{
    protected static string $resource = CashReceiptResource::class;

    public function create(...$args): void
    {
        try {
            parent::create(...$args);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validation failed.');

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        } catch (QueryException $e) {
            $message = $this->resolveErrorMessage($e);

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'receipt_number' => $message,
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Save Failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function resolveErrorMessage(QueryException $e): string
    {
        $sqlState = $e->getSQLState() ?? '';
        $message = $e->getMessage();

        if (str_contains($message, 'cash_receipts_company_receipt_number_unique') ||
            str_contains($message, 'cash_receipts_receipt_number_unique') ||
            $sqlState === '23505') {
            return __('Receipt number is already used. Please generate a new code or use a different one.');
        }

        return __('An error occurred while saving. Please try again.');
    }

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
            $items = $data['items'] ?? [];

            
            
            if (empty($data['to_account_id'])) {
                throw ValidationException::withMessages([
                    'to_account_id' => __('Account must be selected.'),
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
                        Notification::make()
                            ->title(__('Error'))
                            ->body(__('User is not authenticated.'))
                            ->danger()
                            ->send();
                        
                        throw ValidationException::withMessages([
                            'to_account_id' => __('User is not authenticated.'),
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
            foreach ($items as $item) {
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
                $glAccountId = $data['to_account_id'];

                $status = 'draft';

                $receipt = $service->createCashIn([
                    'date' => $data['date'],
                    'reference_no' => $data['reference_no'] ?? null,
                    'receipt_number' => $data['receipt_number'] ?? null,
                    'description' => $data['description'] ?? null,
                    'items' => $cleanedItems,
                    'to_account_id' => $glAccountId, 
                    'company_id' => $data['company_id'] ?? null,
                    'status' => $status,
                    'department_id' => $data['department_id'] ?? $defaultDepartment->id,
                    'cost_center_id' => $data['cost_center_id'] ?? $defaultCostCenter->id,
                ]);
                
                $this->record = $receipt;
                
                return $receipt;
            } catch (\InvalidArgumentException $e) {
                Notification::make()
                    ->title(__('Error'))
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                throw ValidationException::withMessages([
                    'to_account_id' => $e->getMessage(), 
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating cash receipt: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            
            Notification::make()
                ->title(__('Error'))
                ->body(__('An error occurred: ' . $e->getMessage()))
                ->danger()
                ->send();
            
            throw ValidationException::withMessages([
                'to_account_id' => __('An error occurred. Please check the log for details.'),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
