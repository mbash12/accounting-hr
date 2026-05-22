<?php

namespace App\Filament\Resources\OvertimeLogs;

use App\Filament\Resources\OvertimeLogs\Pages\CreateOvertimeLog;
use App\Filament\Resources\OvertimeLogs\Pages\EditOvertimeLog;
use App\Filament\Resources\OvertimeLogs\Pages\ListOvertimeLogs;
use App\Filament\Resources\OvertimeLogs\Schemas\OvertimeLogForm;
use App\Filament\Resources\OvertimeLogs\Tables\OvertimeLogsTable;
use App\Models\OvertimeLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OvertimeLogResource extends Resource
{
    protected static ?string $model = OvertimeLog::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Overtime');
    }

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('Overtime');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Overtime');
    }

    public static function form(Schema $schema): Schema
    {
        return OvertimeLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OvertimeLogsTable::configure($table);
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
            'index' => ListOvertimeLogs::route('/'),
            'create' => CreateOvertimeLog::route('/create'),
            'edit' => EditOvertimeLog::route('/{record}/edit'),
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
