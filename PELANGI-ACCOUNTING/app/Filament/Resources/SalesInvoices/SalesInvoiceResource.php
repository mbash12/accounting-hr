<?php

namespace App\Filament\Resources\SalesInvoices;

use App\Filament\Resources\SalesInvoices\Pages\CreateSalesInvoice;
use App\Filament\Resources\SalesInvoices\Pages\EditSalesInvoice;
use App\Filament\Resources\SalesInvoices\Pages\ListSalesInvoices;
use App\Filament\Resources\SalesInvoices\Pages\ViewSalesInvoice;
use App\Filament\Resources\SalesInvoices\Schemas\SalesInvoiceForm;
use App\Filament\Resources\SalesInvoices\Tables\SalesInvoicesTable;
use App\Models\SalesInvoice;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesInvoiceResource extends Resource
{
    protected static ?string $model = SalesInvoice::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationLabel(): string
    {
        return 'Faktur Penjualan';
    }

    public static function getModelLabel(): string
    {
        return 'Faktur Penjualan';
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getPluralModelLabel(): string
    {
        return 'Faktur Penjualan';
    }

    public static function form(Schema $schema): Schema
    {
        return SalesInvoiceForm::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return SalesInvoicesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId) {
                // ALWAYS include sales invoices with null company_id
                $query->whereNull('company_id');
                
                // Add company-specific filtering
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    // When specific company selected, show that company's invoices + null company_id invoices
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    // When 'all' selected or no session, show all invoices
                    $query->orWhereNotNull('company_id');
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
            'index' => ListSalesInvoices::route('/'),
            'create' => CreateSalesInvoice::route('/create'),
            'view' => ViewSalesInvoice::route('/{record}'),
            'edit' => EditSalesInvoice::route('/{record}/edit'),
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
