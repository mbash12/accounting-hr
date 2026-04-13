<?php

namespace App\Filament\Resources\CashDisbursements;

use App\Filament\Resources\CashDisbursements\Pages\CreateCashDisbursement;
use App\Filament\Resources\CashDisbursements\Pages\EditCashDisbursement;
use App\Filament\Resources\CashDisbursements\Pages\ListCashDisbursements;
use App\Filament\Resources\CashDisbursements\Schemas\CashDisbursementForm;
use App\Filament\Resources\CashDisbursements\Tables\CashDisbursementsTable;
use App\Models\CashDisbursement;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashDisbursementResource extends Resource
{
    protected static ?string $model = CashDisbursement::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Cash & Bank');
    }

    public static function getNavigationLabel(): string
    {
        return __('Expenses');
    }

    public static function getModelLabel(): string
    {
        return __('Expense');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Expenses');
    }

    public static function form(Schema $schema): Schema
    {
        return CashDisbursementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashDisbursementsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->when(
                session('selected_company_id') && session('selected_company_id') !== 'all',
                fn(Builder $query) => $query->where('company_id', session('selected_company_id'))
            );
        // When 'all' is selected or no company selected, show all records (both global and company-specific)
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
            'index' => ListCashDisbursements::route('/'),
            'create' => CreateCashDisbursement::route('/create'),
            'edit' => EditCashDisbursement::route('/{record}/edit'),
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
