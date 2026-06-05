<?php

namespace App\Filament\Resources\UnitCategories;

use App\Filament\Resources\UnitCategories\Pages\CreateUnitCategory;
use App\Filament\Resources\UnitCategories\Pages\EditUnitCategory;
use App\Filament\Resources\UnitCategories\Pages\ListUnitCategories;
use App\Filament\Resources\UnitCategories\Schemas\UnitCategoryForm;
use App\Filament\Resources\UnitCategories\Tables\UnitCategoriesTable;
use App\Models\UnitCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitCategoryResource extends Resource
{
    protected static ?string $model = UnitCategory::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string
    {
        return __('UOM Categories');
    }

    public static function getModelLabel(): string
    {
        return __('UOM Category');
    }

    public static function getNavigationSort(): int
    {
        return 0;
    }

    public static function getPluralModelLabel(): string
    {
        return __('UOM Categories');
    }

    public static function form(Schema $schema): Schema
    {
        return UnitCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitCategoriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    if ($user) {
                        $userCompanyIds = $user->companies()->pluck('companies.id');
                        if ($userCompanyIds->isNotEmpty()) {
                            $query->whereIn('company_id', $userCompanyIds);
                        }
                    }
                }
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnitCategories::route('/'),
            'create' => CreateUnitCategory::route('/create'),
            'edit' => EditUnitCategory::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
