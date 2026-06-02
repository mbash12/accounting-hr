<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PostingCenter extends Page
{
    protected static ?string $navigationLabel = 'Posting Center';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.posting-center';

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public static function getNavigationLabel(): string
    {
        return __('Posting Center');
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return __('Posting Center');
    }
}
