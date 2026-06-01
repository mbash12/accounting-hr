<?php

namespace App\Filament\Resources\DeferredRevenues;

use App\Filament\Resources\DeferredRevenues\Pages\CreateDeferredRevenue;
use App\Filament\Resources\DeferredRevenues\Pages\EditDeferredRevenue;
use App\Filament\Resources\DeferredRevenues\Pages\ListDeferredRevenues;
use App\Filament\Resources\DeferredRevenues\Schemas\DeferredRevenueForm;
use App\Filament\Resources\DeferredRevenues\Tables\DeferredRevenuesTable;
use App\Models\DeferredRevenue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DeferredRevenueResource extends Resource
{
    protected static ?string $model = DeferredRevenue::class;

    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';

    public static function getNavigationLabel(): string
    {
        return __('Amortisasi Pendapatan');
    }

    public static function getModelLabel(): string
    {
        return __('Amortisasi Pendapatan');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Amortisasi Pendapatan');
    }

    public static function getNavigationGroup(): string
    {
        return __('General Ledger');
    }

    public static function form(Schema $schema): Schema
    {
        return DeferredRevenueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeferredRevenuesTable::configure($table);
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
            RelationManagers\SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeferredRevenues::route('/'),
            'create' => CreateDeferredRevenue::route('/create'),
            'edit' => EditDeferredRevenue::route('/{record}/edit'),
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
