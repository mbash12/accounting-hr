<?php

namespace App\Filament\Resources\CashReceipts;

use App\Filament\Resources\CashReceipts\Pages\CreateCashReceipt;
use App\Filament\Resources\CashReceipts\Pages\EditCashReceipt;
use App\Filament\Resources\CashReceipts\Pages\ListCashReceipts;
use App\Filament\Resources\CashReceipts\Schemas\CashReceiptForm;
use App\Filament\Resources\CashReceipts\Tables\CashReceiptsTable;
use App\Models\CashReceipt;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashReceiptResource extends Resource
{
    protected static ?string $model = CashReceipt::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Cash & Bank');
    }

    public static function getNavigationLabel(): string
    {
        return __('Cash Receipt');
    }

    public static function getModelLabel(): string
    {
        return __('Cash Receipt');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Cash Receipt');
    }

    public static function form(Schema $schema): Schema
    {
        return CashReceiptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashReceiptsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');

        return parent::getEloquentQuery()
            ->with(['toAccount', 'company']) 
            ->where(function (Builder $query) use ($selectedCompanyId) {
                $query->whereNull('company_id');

                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    $query->orWhereNotNull('company_id');
                }
            })
            ->orderBy('date', 'desc'); 
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
            'index' => ListCashReceipts::route('/'),
            'create' => CreateCashReceipt::route('/create'),
            'edit' => EditCashReceipt::route('/{record}/edit'),
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
