<?php

namespace App\Filament\Resources\ProductGroups;

use App\Filament\Resources\ProductGroups\Pages\CreateProductGroup;
use App\Filament\Resources\ProductGroups\Pages\EditProductGroup;
use App\Filament\Resources\ProductGroups\Pages\ListProductGroups;
use App\Filament\Resources\ProductGroups\Schemas\ProductGroupForm;
use App\Filament\Resources\ProductGroups\Tables\ProductGroupsTable;
use App\Models\ProductGroup;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductGroupResource extends Resource
{
    protected static ?string $model = ProductGroup::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function getNavigationLabel(): string
    {
        return __('Product Groups');
    }

    public static function getModelLabel(): string
    {
        return __('Product Group');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Product Groups');
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function form(Schema $schema): Schema
    {
        return ProductGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductGroupsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's product groups
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only product groups from user's assigned companies
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
            'index' => ListProductGroups::route('/'),
            'create' => CreateProductGroup::route('/create'),
            'edit' => EditProductGroup::route('/{record}/edit'),
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
