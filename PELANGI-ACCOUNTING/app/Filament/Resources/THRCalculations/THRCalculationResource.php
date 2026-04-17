<?php

namespace App\Filament\Resources\THRCalculations;

use App\Filament\Resources\THRCalculations\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\THRCalculations\Schemas\THRCalculationForm;
use App\Filament\Resources\THRCalculations\Tables\THRCalculationsTable;
use App\Models\THRCalculation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\THRCalculations\Pages;

class THRCalculationResource extends Resource
{
    protected static ?string $model = THRCalculation::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('THR');
    }

    protected static ?int $navigationSort = 9;

    public static function getModelLabel(): string
    {
        return __('THR');
    }

    public static function getPluralModelLabel(): string
    {
        return __('THR');
    }

    public static function form(Schema $schema): Schema
    {
        return THRCalculationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return THRCalculationsTable::configure($table);
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
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTHRCalculations::route('/'),
            'create' => Pages\CreateTHRCalculation::route('/create'),
            'edit' => Pages\EditTHRCalculation::route('/{record}/edit'),
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
