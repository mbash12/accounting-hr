<?php

namespace App\Filament\Resources\SalesDeliveries;

use App\Filament\Resources\SalesDeliveries\Pages\CreateSalesDelivery;
use App\Filament\Resources\SalesDeliveries\Pages\EditSalesDelivery;
use App\Filament\Resources\SalesDeliveries\Pages\ListSalesDeliveries;
use App\Filament\Resources\SalesDeliveries\Pages\ViewDeliveryDocument;
use App\Filament\Resources\SalesDeliveries\Schemas\SalesDeliveryForm;
use App\Filament\Resources\SalesDeliveries\Tables\SalesDeliveriesTable;
use App\Models\DeliveryDocument;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesDeliveryResource extends Resource
{
    protected static ?string $model = DeliveryDocument::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('Sales Deliveries');
    }

    public static function getModelLabel(): string
    {
        return __('Sales Deliveries');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Sales Deliveries');
    }

    public static function form(Schema $schema): Schema
    {
        return SalesDeliveryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesDeliveriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId) {
                // ALWAYS include sales deliveries with null company_id
                $query->whereNull('company_id');
                
                // Add company-specific filtering
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    // When specific company selected, show that company's deliveries + null company_id deliveries
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    // When 'all' selected or no session, show all deliveries
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
            'index' => ListSalesDeliveries::route('/'),
            'create' => CreateSalesDelivery::route('/create'),
            'view' => ViewDeliveryDocument::route('/{record}'),
            'edit' => EditSalesDelivery::route('/{record}/edit'),
        ];
    }
}
