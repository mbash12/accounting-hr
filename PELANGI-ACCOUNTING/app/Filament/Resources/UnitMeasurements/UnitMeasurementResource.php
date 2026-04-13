<?php

namespace App\Filament\Resources\UnitMeasurements;

use App\Filament\Resources\UnitMeasurements\Pages\CreateUnitMeasurement;
use App\Filament\Resources\UnitMeasurements\Pages\EditUnitMeasurement;
use App\Filament\Resources\UnitMeasurements\Pages\ListUnitMeasurements;
use App\Filament\Resources\UnitMeasurements\Schemas\UnitMeasurementForm;
use App\Filament\Resources\UnitMeasurements\Tables\UnitMeasurementsTable;
use App\Models\Unit;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitMeasurementResource extends Resource
{
    protected static ?string $model = Unit::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string
    {
        return __('Unit Measurements');
    }

    public static function getModelLabel(): string
    {
        return __('Unit Measurement');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Units');
    }

    public static function form(Schema $schema): Schema
    {
        return UnitMeasurementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitMeasurementsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's units
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only units from user's assigned companies
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
            'index' => ListUnitMeasurements::route('/'),
            'create' => CreateUnitMeasurement::route('/create'),
            'edit' => EditUnitMeasurement::route('/{record}/edit'),
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
