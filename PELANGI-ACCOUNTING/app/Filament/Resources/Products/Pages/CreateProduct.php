<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function mount(): void
    {
        // Redirect away from create if the selected company is PPN (read-only)
        if (ProductResource::isReadOnly()) {
            $this->redirect(ProductResource::getUrl('index'));
        }

        parent::mount();
    }
}
