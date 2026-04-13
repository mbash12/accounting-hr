<?php

namespace App\Filament\Resources\FixedAssets\Pages;

use App\Filament\Resources\FixedAssets\FixedAssetResource;
use App\Models\FixedAssetTransaction;
use Filament\Resources\Pages\CreateRecord;

class CreateFixedAsset extends CreateRecord
{
    protected static string $resource = FixedAssetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->createAcquisition = $data['create_acquisition_transaction'] ?? false;
        unset($data['create_acquisition_transaction']);
        return $data;
    }

    protected bool $createAcquisition = false;

    protected function afterCreate(): void
    {
        if ($this->createAcquisition && $this->record->acquisition_value > 0) {
            FixedAssetTransaction::create([
                'fixed_asset_id' => $this->record->id,
                'company_id' => $this->record->company_id,
                'transaction_type' => 'acquisition',
                'date' => $this->record->acquisition_date,
                'reference_no' => 'ACQ-' . $this->record->code,
                'journal_value' => $this->record->acquisition_value,
                'asset_value' => $this->record->acquisition_value,
                'difference' => 0,
                'description' => 'Perolehan aset: ' . $this->record->name,
                'create_journal' => true,
                'created_by_user_id' => auth()->id(),
            ]);
        }
    }
}
