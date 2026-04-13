<?php

namespace App\Filament\Resources\TransactionClassifications\Pages;

use App\Filament\Resources\TransactionClassifications\TransactionClassificationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTransactionClassification extends CreateRecord
{
    protected static string $resource = TransactionClassificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = Auth::id();

        return $data;
    }
}



