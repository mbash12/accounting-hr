<?php

namespace App\Filament\Resources\FixedAssetCategories\Pages;

use App\Filament\Resources\FixedAssetCategories\FixedAssetCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFixedAssetCategories extends ListRecords
{
    protected static string $resource = FixedAssetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
