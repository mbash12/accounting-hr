<?php

namespace App\Filament\Resources\ReceivableLists;

use App\Filament\Resources\ReceivableLists\Pages\ListReceivableLists;
use App\Filament\Resources\ReceivableLists\Pages\ViewReceivableDetail;
use App\Filament\Resources\ReceivableLists\Tables\ReceivableListsTable;
use App\Models\Contact;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ReceivableListResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|\BackedEnum|null $navigationIcon = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('Receivable List');
    }

    public static function getModelLabel(): string
    {
        return __('Receivable');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Receivables');
    }

    public static function table(Table $table): Table
    {
        return ReceivableListsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        $query = parent::getEloquentQuery()
            ->where('contacts.is_customer', true)
            ->where('contacts.is_active', true);
        
        if ($selectedCompanyId && $selectedCompanyId !== 'all') {
            $query->where(function ($q) use ($selectedCompanyId) {
                $q->where('contacts.company_id', $selectedCompanyId)
                    ->orWhereNull('contacts.company_id');
            });
        }
        
        $query->select('contacts.*')
            ->selectRaw('
                COALESCE(SUM(sales_invoices.total_amount), 0) as total_receivable,
                COALESCE(SUM(sales_invoices.paid_amount), 0) as total_paid,
                COALESCE(SUM(sales_invoices.outstanding_amount), 0) as total_outstanding,
                MAX(sales_invoices.date) as latest_invoice_date
            ')
            ->leftJoin('sales_invoices', function ($join) use ($selectedCompanyId) {
                $join->on('contacts.id', '=', 'sales_invoices.customer_id')
                    ->where(function ($q) {
                        $q->where('sales_invoices.outstanding_amount', '>', 0)
                            ->orWhere('sales_invoices.is_paid', false);
                    })
                    ->whereNull('sales_invoices.deleted_at');
                
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $join->where(function ($q) use ($selectedCompanyId) {
                        $q->where('sales_invoices.company_id', $selectedCompanyId)
                            ->orWhereNull('sales_invoices.company_id');
                    });
                }
            })
            ->groupBy('contacts.id')
            ->havingRaw('COALESCE(SUM(sales_invoices.outstanding_amount), 0) > 0')
            ->orderBy('latest_invoice_date', 'desc');
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceivableLists::route('/'),
            'view' => ViewReceivableDetail::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        $query = Contact::query()
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

