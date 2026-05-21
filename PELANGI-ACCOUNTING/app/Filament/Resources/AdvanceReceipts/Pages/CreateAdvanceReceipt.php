<?php

namespace App\Filament\Resources\AdvanceReceipts\Pages;

use App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource;
use App\Services\CashBankService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateAdvanceReceipt extends CreateRecord
{
    protected static string $resource = AdvanceReceiptResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $data['advanceReceiptItems'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages([
                'advanceReceiptItems' => __('At least one item is required.'),
            ]);
        }

        foreach ($items as $index => $item) {
            if (empty($item['transaction_classification_id'])) {
                throw ValidationException::withMessages([
                    "advanceReceiptItems.{$index}.transaction_classification_id" => __('Transaction Classification must be selected for each item.'),
                ]);
            }
        }

        if (empty($data['to_account_id'])) {
            throw ValidationException::withMessages([
                'to_account_id' => __('Cash/Bank Account must be selected.'),
            ]);
        }

        if (empty($data['recipient_id'])) {
            throw ValidationException::withMessages([
                'recipient_id' => __('Recipient must be selected.'),
            ]);
        }

        if (empty($data['reference_no']) && !empty($data['advance_receipt_number'])) {
            $data['reference_no'] = $data['advance_receipt_number'];
        }

        if (empty($data['reference_no'])) {
            $data['reference_no'] = 'AR-' . date('YmdHis');
        }

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            } else {
                $user = auth()->user();
                if ($user) {
                    $firstCompany = $user->companies()->first();
                    if ($firstCompany) {
                        $data['company_id'] = $firstCompany->id;
                    }
                }
            }
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            $service = app(CashBankService::class);
            $advanceReceipt = $service->createAdvanceReceiptWithJournal($data);
            
            Notification::make()
                ->title(__('Success'))
                ->body(__('Advance Receipt created successfully.'))
                ->success()
                ->send();

            return $advanceReceipt;
        } catch (ValidationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            Notification::make()
                ->title(__('Validation Error'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'form' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating advance receipt: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            Notification::make()
                ->title(__('Error'))
                ->body(__('An error occurred: ' . $e->getMessage()))
                ->danger()
                ->send();

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
