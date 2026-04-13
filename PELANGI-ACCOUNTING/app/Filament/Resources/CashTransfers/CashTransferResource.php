<?php

namespace App\Filament\Resources\CashTransfers;

use App\Filament\Resources\CashTransfers\Pages\CreateCashTransfer;
use App\Filament\Resources\CashTransfers\Pages\EditCashTransfer;
use App\Filament\Resources\CashTransfers\Pages\ListCashTransfers;
use App\Filament\Resources\CashTransfers\Schemas\CashTransferForm;
use App\Filament\Resources\CashTransfers\Tables\CashTransfersTable;
use App\Models\CashTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CashTransferResource extends Resource
{
    protected static ?string $model = CashTransfer::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Cash & Bank');
    }

    public static function getModelLabel(): string
    {
        return __('Cash Transfer');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Cash Transfers');
    }

    public static function form(Schema $schema): Schema
    {
        return CashTransferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashTransfersTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
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
            'index' => ListCashTransfers::route('/'),
            'create' => CreateCashTransfer::route('/create'),
            'edit' => EditCashTransfer::route('/{record}/edit'),
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
