<?php

namespace App\Filament\Resources\CashTransfers\Pages;

use App\Filament\Resources\CashTransfers\CashTransferResource;
use App\Models\CashTransfer;
use App\Services\CashBankService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditCashTransfer extends EditRecord
{
    protected static string $resource = CashTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected ?string $oldStatus = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldStatus = $this->record->status;
        
        $isPosted = $data['is_posted'] ?? false;
        $data['status'] = $isPosted ? 'posted' : 'draft';
        unset($data['is_posted']); // Remove toggle from data

        if (empty($data['company_id'])) {
            $selectedCompanyId = session('selected_company_id');
            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                $data['company_id'] = $selectedCompanyId;
            }
        }

        if (isset($data['from_account_id']) && isset($data['to_account_id'])) {
            if ($data['from_account_id'] == $data['to_account_id']) {
                throw ValidationException::withMessages([
                    'to_account_id' => 'Source and destination accounts cannot be the same.',
                ]);
            }
        }

        return parent::mutateFormDataBeforeSave($data);
    }

    protected function afterSave(): void
    {
        $oldStatus = $this->oldStatus;
        $this->record->refresh();
        $newStatus = $this->record->status;

        if ($oldStatus !== $newStatus || $newStatus === 'posted') {
            DB::transaction(function () {
                $service = app(CashBankService::class);
                $service->createJournalEntryForRecord($this->record);
            });
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
