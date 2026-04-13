<?php

namespace App\Filament\Resources\AdvanceReceipts;

use App\Filament\Resources\AdvanceReceipts\Pages\CreateAdvanceReceipt;
use App\Filament\Resources\AdvanceReceipts\Pages\EditAdvanceReceipt;
use App\Filament\Resources\AdvanceReceipts\Pages\ListAdvanceReceipts;
use App\Filament\Resources\AdvanceReceipts\Schemas\AdvanceReceiptForm;
use App\Filament\Resources\AdvanceReceipts\Tables\AdvanceReceiptsTable;
use App\Models\AdvanceReceipt;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvanceReceiptResource extends Resource
{
    protected static ?string $model = AdvanceReceipt::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Cash & Bank');
    }

    public static function getNavigationLabel(): string
    {
        return __('Advance Receipts');
    }

    public static function getModelLabel(): string
    {
        return __('Advance Receipt');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Advance Receipts');
    }

    public static function form(Schema $schema): Schema
    {
        return AdvanceReceiptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvanceReceiptsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->with(['recipient', 'toAccount', 'company', 'createdByUser'])
            ->when(
                $selectedCompanyId && $selectedCompanyId !== 'all',
                fn(Builder $query) => $query->where('company_id', $selectedCompanyId)
            )
            ->when(
                !$selectedCompanyId || $selectedCompanyId === 'all',
                function (Builder $query) use ($user) {
                    if ($user) {
                        $userCompanyIds = $user->companies()->pluck('companies.id');
                        if ($userCompanyIds->isNotEmpty()) {
                            $query->whereIn('company_id', $userCompanyIds);
                        } else {
                            $query->whereRaw('1 = 0');
                        }
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                }
            );
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
            'index' => ListAdvanceReceipts::route('/'),
            'create' => CreateAdvanceReceipt::route('/create'),
            'edit' => EditAdvanceReceipt::route('/{record}/edit'),
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
