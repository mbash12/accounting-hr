<?php

namespace App\Filament\Resources\AdvanceDisbursements\Pages;

use App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource;
use App\Services\CashBankService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateAdvanceDisbursement extends CreateRecord
{
    protected static string $resource = AdvanceDisbursementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['reference_no']) && !empty($data['advance_number'])) {
            $data['reference_no'] = $data['advance_number'];
        }

        if (empty($data['reference_no'])) {
            $data['reference_no'] = 'AD-' . date('YmdHis');
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

        $items = $data['items'] ?? [];
        if (!empty($items)) {
            $total = 0;
            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $total += is_numeric($amount) ? (float) $amount : 0;
            }
            $data['total'] = $total;
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            $items = $data['items'] ?? [];
            
            if (!empty($items) && is_array($items)) {
                $items = array_values($items); // Ensure sequential array
            }
            
            if (empty($items)) {
                throw ValidationException::withMessages([
                    'items' => __('Minimal satu item diperlukan.'),
                ]);
            }

            foreach ($items as $index => $item) {
                if (empty($item['transaction_classification_id'])) {
                    throw ValidationException::withMessages([
                        "items.{$index}.transaction_classification_id" => __('Transaction Classification harus dipilih untuk setiap item.'),
                    ]);
                }
                if (empty($item['amount']) || (float)($item['amount'] ?? 0) <= 0) {
                    throw ValidationException::withMessages([
                        "items.{$index}.amount" => __('Amount harus lebih dari 0 untuk setiap item.'),
                    ]);
                }
            }

            if (empty($data['from_account_id'])) {
                throw ValidationException::withMessages([
                    'from_account_id' => __('Cash/Bank Account harus dipilih.'),
                ]);
            }

            if (empty($data['recipient_id'])) {
                throw ValidationException::withMessages([
                    'recipient_id' => __('Recipient harus dipilih.'),
                ]);
            }

            if (empty($data['reference_no']) && !empty($data['advance_number'])) {
                $data['reference_no'] = $data['advance_number'];
            }

            if (empty($data['reference_no'])) {
                $data['reference_no'] = 'AD-' . date('YmdHis');
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

            $total = 0;
            foreach ($items as $item) {
                $amount = $item['amount'] ?? 0;
                if (is_string($amount)) {
                    $amount = str_replace(['.', ','], ['', '.'], $amount);
                }
                $total += is_numeric($amount) ? (float) $amount : 0;
            }
            $data['total'] = $total;

            $service = app(CashBankService::class);
            $advanceDisbursement = $service->createAdvanceDisbursementWithJournal($data);
            
            Notification::make()
                ->title(__('Success'))
                ->body(__('Advance Disbursement berhasil dibuat.'))
                ->success()
                ->send();

            return $advanceDisbursement;
        } catch (ValidationException $e) {
            Notification::make()
                ->title(__('Validation Error'))
                ->body(__('Mohon periksa kembali data yang diisi.'))
                ->danger()
                ->send();
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
            Log::error('Error creating advance disbursement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            Notification::make()
                ->title(__('Error'))
                ->body(__('Terjadi kesalahan: ' . $e->getMessage()))
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'form' => __('Terjadi kesalahan: ' . $e->getMessage()),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
