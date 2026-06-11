<?php

namespace App\Filament\Resources\PayableLists;

use App\Filament\Resources\PayableLists\Pages\ListPayableLists;
use App\Filament\Resources\PayableLists\Pages\ViewPayableDetail;
use App\Filament\Resources\PayableLists\Tables\PayableListsTable;
use App\Models\PayableContact;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayableListResource extends Resource
{
    protected static ?string $model = PayableContact::class;

    protected static string|\BackedEnum|null $navigationIcon = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Purchasing');
    }

    public static function getNavigationLabel(): string
    {
        return __('Accounts Payable');
    }

    public static function getModelLabel(): string
    {
        return __('Payable');
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Accounts Payable');
    }

    public static function table(Table $table): Table
    {
        return PayableListsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        $query = parent::getEloquentQuery();
            // ->where('contacts.is_supplier', true)
            // ->where('contacts.is_active', true);
        
        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $query->where(function ($q) use ($selectedCompanyId) {
                $q->where('contacts.company_id', $selectedCompanyId)
                    ->orWhereNull('contacts.company_id');
            });
        }
        
        $query->select('contacts.*')
            ->selectRaw('
                COALESCE(SUM(CAST(purchase_invoices.total AS DECIMAL)), 0) as total_payable,
                COALESCE(SUM(CAST(purchase_invoices.paid_amount AS DECIMAL)), 0) as total_paid,
                COALESCE(SUM(CAST(purchase_invoices.outstanding_amount AS DECIMAL)), 0) as total_outstanding,
                MAX(purchase_invoices.date) as latest_invoice_date
            ')
            ->leftJoin('purchase_invoices', function ($join) use ($selectedCompanyId) {
                $join->on('contacts.id', '=', 'purchase_invoices.supplier_id')
                    ->whereNotIn('purchase_invoices.status', ['draft','paid', 'cancelled'])
                    ->whereRaw('CAST(purchase_invoices.outstanding_amount AS DECIMAL) > 0')
                    ->whereNull('purchase_invoices.deleted_at');
                
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $join->where(function ($q) use ($selectedCompanyId) {
                        $q->where('purchase_invoices.company_id', $selectedCompanyId)
                            ->orWhereNull('purchase_invoices.company_id');
                    });
                }
            })
            ->groupBy('contacts.id')
            ->havingRaw('COALESCE(SUM(CAST(purchase_invoices.outstanding_amount AS DECIMAL)), 0) > 0')
            ->orderBy('latest_invoice_date', 'desc');
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayableLists::route('/'),
            'view' => ViewPayableDetail::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        // Start fresh without the JOIN from getEloquentQuery() to avoid ambiguous "id"
        $query = static::getModel()::query()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
        
        $selectedCompanyId = session('selected_company_id');
        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $query->where(function ($q) use ($selectedCompanyId) {
                $q->where('contacts.company_id', $selectedCompanyId)
                    ->orWhereNull('contacts.company_id');
            });
        }
        
        return $query;
    }
}

