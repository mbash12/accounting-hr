<?php

namespace App\Filament\Resources\UnitCategories\Pages;

use App\Filament\Resources\UnitCategories\UnitCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditUnitCategory extends EditRecord
{
    protected static string $resource = UnitCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
