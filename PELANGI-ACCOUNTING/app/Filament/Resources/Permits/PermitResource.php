<?php

namespace App\Filament\Resources\Permits;

use App\Filament\Resources\Permits\Pages\CreatePermit;
use App\Filament\Resources\Permits\Pages\EditPermit;
use App\Filament\Resources\Permits\Pages\ListPermits;
use App\Filament\Resources\Permits\Schemas\PermitForm;
use App\Filament\Resources\Permits\Tables\PermitsTable;
use App\Models\Permit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermitResource extends Resource
{
    protected static ?string $model = Permit::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Izin / Cuti');
    }

    protected static ?int $navigationSort = 7;

    public static function getModelLabel(): string
    {
        return __('Izin / Cuti');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Izin / Cuti');
    }

    public static function form(Schema $schema): Schema
    {
        return PermitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermitsTable::configure($table);
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
            'index' => ListPermits::route('/'),
            'create' => CreatePermit::route('/create'),
            'edit' => EditPermit::route('/{record}/edit'),
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
