<?php

namespace App\Filament\Resources\PayablePayments;

use App\Filament\Resources\PayablePayments\Pages\CreatePayablePayment;
use App\Filament\Resources\PayablePayments\Pages\EditPayablePayment;
use App\Filament\Resources\PayablePayments\Pages\ListPayablePayments;
use App\Filament\Resources\PayablePayments\Schemas\PayablePaymentForm;
use App\Filament\Resources\PayablePayments\Tables\PayablePaymentsTable;
use App\Models\PayablePayment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayablePaymentResource extends Resource
{
    protected static ?string $model = PayablePayment::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Purchasing');
    }

    public static function getNavigationLabel(): string
    {
        return __('Payable Payments');
    }

    public static function getModelLabel(): string
    {
        return __('Payable Payments');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Payable Payments');
    }

    public static function form(Schema $schema): Schema
    {
        return PayablePaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayablePaymentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->with(['supplier', 'bankAccount', 'company'])
            ->where(function ($query) use ($selectedCompanyId) {
                // ALWAYS include payable payments with null company_id
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
            'index' => ListPayablePayments::route('/'),
            'create' => CreatePayablePayment::route('/create'),
            'edit' => EditPayablePayment::route('/{record}/edit'),
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

