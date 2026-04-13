<?php

namespace App\Filament\Resources\OvertimeRules;

use App\Filament\Resources\OvertimeRules\Pages\CreateOvertimeRule;
use App\Filament\Resources\OvertimeRules\Pages\EditOvertimeRule;
use App\Filament\Resources\OvertimeRules\Pages\ListOvertimeRules;
use App\Filament\Resources\OvertimeRules\Schemas\OvertimeRuleForm;
use App\Filament\Resources\OvertimeRules\Tables\OvertimeRulesTable;
use App\Models\OvertimeRule;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OvertimeRuleResource extends Resource
{
    protected static ?string $model = OvertimeRule::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Aturan Lembur');
    }

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('Aturan Lembur');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Aturan Lembur');
    }

    public static function form(Schema $schema): Schema
    {
        return OvertimeRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OvertimeRulesTable::configure($table);
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
            'index' => ListOvertimeRules::route('/'),
            'create' => CreateOvertimeRule::route('/create'),
            'edit' => EditOvertimeRule::route('/{record}/edit'),
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
