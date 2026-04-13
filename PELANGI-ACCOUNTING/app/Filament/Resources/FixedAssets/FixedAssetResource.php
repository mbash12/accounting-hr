<?php

namespace App\Filament\Resources\FixedAssets;

use App\Filament\Resources\FixedAssets\Pages\CreateFixedAsset;
use App\Filament\Resources\FixedAssets\Pages\EditFixedAsset;
use App\Filament\Resources\FixedAssets\Pages\ListFixedAssets;
use App\Filament\Resources\FixedAssets\Schemas\FixedAssetForm;
use App\Filament\Resources\FixedAssets\Tables\FixedAssetsTable;
use App\Models\FixedAsset;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FixedAssetResource extends Resource
{
    protected static ?string $model = FixedAsset::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function getNavigationLabel(): string
    {
        return 'Harta Tetap';
    }

    public static function getModelLabel(): string
    {
        return 'Harta Tetap';
    }

    public static function getNavigationSort(): int
    {
        return 8;
    }

    public static function getPluralModelLabel(): string
    {
        return 'Harta Tetap';
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function form(Schema $schema): Schema
    {
        return FixedAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FixedAssetsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's assets
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only assets from user's assigned companies
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
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFixedAssets::route('/'),
            'create' => CreateFixedAsset::route('/create'),
            'edit' => EditFixedAsset::route('/{record}/edit'),
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
