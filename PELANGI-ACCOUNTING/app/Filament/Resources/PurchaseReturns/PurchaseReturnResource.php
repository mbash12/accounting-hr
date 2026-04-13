<?php

namespace App\Filament\Resources\PurchaseReturns;

use App\Filament\Resources\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\EditPurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Filament\Resources\PurchaseReturns\Pages\ViewPurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Schemas\PurchaseReturnForm;
use App\Filament\Resources\PurchaseReturns\Tables\PurchaseReturnsTable;
use App\Models\PurchaseReturn;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseReturnResource extends Resource
{
    protected static ?string $model = PurchaseReturn::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Purchasing');
    }

    public static function getNavigationLabel(): string
    {
        return __('Purchase Returns');
    }

    public static function getModelLabel(): string
    {
        return __('Purchase Return');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Purchase Returns');
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseReturnsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        
        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId) {
                // ALWAYS include purchase returns with null company_id
                $query->whereNull('company_id');
                
                // Add company-specific filtering
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    // When specific company selected, show that company's returns + null company_id returns
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    // When 'all' selected or no session, show all returns
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
            'index' => ListPurchaseReturns::route('/'),
            'create' => CreatePurchaseReturn::route('/create'),
            'view' => ViewPurchaseReturn::route('/{record}'),
            'edit' => EditPurchaseReturn::route('/{record}/edit'),
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
