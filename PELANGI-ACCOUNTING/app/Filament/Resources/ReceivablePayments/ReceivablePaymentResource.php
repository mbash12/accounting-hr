<?php

namespace App\Filament\Resources\ReceivablePayments;

use App\Filament\Resources\ReceivablePayments\Pages\CreateReceivablePayment;
use App\Filament\Resources\ReceivablePayments\Pages\EditReceivablePayment;
use App\Filament\Resources\ReceivablePayments\Pages\ListReceivablePayments;
use App\Filament\Resources\ReceivablePayments\Schemas\ReceivablePaymentForm;
use App\Filament\Resources\ReceivablePayments\Tables\ReceivablePaymentsTable;
use App\Models\ReceivablePayment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceivablePaymentResource extends Resource
{
    protected static ?string $model = ReceivablePayment::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('Receivable Payments');
    }

    public static function getModelLabel(): string
    {
        return __('Receivable Payment');
    }

    public static function getNavigationSort(): int
    {
        return 6;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Receivable Payments');
    }

    public static function form(Schema $schema): Schema
    {
        return ReceivablePaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceivablePaymentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->with(['customer', 'bankAccount', 'company'])
            ->where(function ($query) use ($selectedCompanyId) {
                // ALWAYS include receivable payments with null company_id
                $query->whereNull('company_id');
                
                // Add company-specific filtering
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    // When specific company selected, show that company's payments + null company_id payments
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    // When 'all' selected or no session, show all payments
                    $query->orWhereNotNull('company_id');
                }
            })
            ->orderBy('payment_date', 'desc');
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
            'index' => ListReceivablePayments::route('/'),
            'create' => CreateReceivablePayment::route('/create'),
            'edit' => EditReceivablePayment::route('/{record}/edit'),
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

