<?php

namespace App\Filament\Resources\SalaryComponents;

use App\Filament\Resources\SalaryComponents\Pages\CreateSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\EditSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\ListSalaryComponents;
use App\Filament\Resources\SalaryComponents\Schemas\SalaryComponentForm;
use App\Filament\Resources\SalaryComponents\Tables\SalaryComponentsTable;
use App\Models\SalaryComponent;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryComponentResource extends Resource
{
    protected static ?string $model = SalaryComponent::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Komponen Gaji');
    }

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('Komponen Gaji');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Komponen Gaji');
    }

    public static function form(Schema $schema): Schema
    {
        return SalaryComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryComponentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return \App\Services\CompanyFilterService::applyCompanyFilter(parent::getEloquentQuery());
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
            'index' => ListSalaryComponents::route('/'),
            'create' => CreateSalaryComponent::route('/create'),
            'edit' => EditSalaryComponent::route('/{record}/edit'),
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
