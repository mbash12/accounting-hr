<?php

namespace App\Filament\Resources\EmployeeLeaveQuotas;

use App\Filament\Resources\EmployeeLeaveQuotas\Pages\CreateEmployeeLeaveQuota;
use App\Filament\Resources\EmployeeLeaveQuotas\Pages\EditEmployeeLeaveQuota;
use App\Filament\Resources\EmployeeLeaveQuotas\Pages\ListEmployeeLeaveQuotas;
use App\Filament\Resources\EmployeeLeaveQuotas\Schemas\EmployeeLeaveQuotaForm;
use App\Filament\Resources\EmployeeLeaveQuotas\Tables\EmployeeLeaveQuotasTable;
use App\Models\EmployeeLeaveQuota;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeLeaveQuotaResource extends Resource
{
    protected static ?string $model = EmployeeLeaveQuota::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Leave Quota');
    }

    protected static ?int $navigationSort = 12;

    public static function getModelLabel(): string
    {
        return __('Leave Quota');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Leave Quota');
    }

    public static function form(Schema $schema): Schema
    {
        return EmployeeLeaveQuotaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeLeaveQuotasTable::configure($table);
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
            'index' => ListEmployeeLeaveQuotas::route('/'),
            'create' => CreateEmployeeLeaveQuota::route('/create'),
            'edit' => EditEmployeeLeaveQuota::route('/{record}/edit'),
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
