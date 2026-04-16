<?php

namespace App\Filament\Resources\BonusCalculations;

use App\Filament\Resources\BonusCalculations\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\BonusCalculations\Schemas\BonusCalculationForm;
use App\Filament\Resources\BonusCalculations\Tables\BonusCalculationsTable;
use App\Models\BonusCalculation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BonusCalculations\Pages;

class BonusCalculationResource extends Resource
{
    protected static ?string $model = BonusCalculation::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Bonus');
    }

    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return __('Bonus');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bonus');
    }

    public static function form(Schema $schema): Schema
    {
        return BonusCalculationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonusCalculationsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return \App\Services\CompanyFilterService::applyCompanyFilter(parent::getEloquentQuery());
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBonusCalculations::route('/'),
            'create' => Pages\CreateBonusCalculation::route('/create'),
            'edit' => Pages\EditBonusCalculation::route('/{record}/edit'),
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
