<?php

namespace App\Filament\Resources\FixedAssetCategories;

use App\Filament\Resources\FixedAssetCategories\Pages\CreateFixedAssetCategory;
use App\Filament\Resources\FixedAssetCategories\Pages\EditFixedAssetCategory;
use App\Filament\Resources\FixedAssetCategories\Pages\ListFixedAssetCategories;
use App\Filament\Resources\FixedAssetCategories\Schemas\FixedAssetCategoryForm;
use App\Filament\Resources\FixedAssetCategories\Tables\FixedAssetCategoriesTable;
use App\Models\FixedAssetCategory;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FixedAssetCategoryResource extends Resource
{
    protected static ?string $model = FixedAssetCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function getNavigationLabel(): string
    {
        return __('Fixed Asset Categories');
    }

    public static function getModelLabel(): string
    {
        return __('Fixed Asset Category');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Fixed Asset Categories');
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function form(Schema $schema): Schema
    {
        return FixedAssetCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FixedAssetCategoriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's categories
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only categories from user's assigned companies
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
            'index' => ListFixedAssetCategories::route('/'),
            'create' => CreateFixedAssetCategory::route('/create'),
            'edit' => EditFixedAssetCategory::route('/{record}/edit'),
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
