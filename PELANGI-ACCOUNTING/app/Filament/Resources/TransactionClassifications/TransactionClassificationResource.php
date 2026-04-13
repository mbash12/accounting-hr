<?php

namespace App\Filament\Resources\TransactionClassifications;

use App\Filament\Resources\TransactionClassifications\Pages\CreateTransactionClassification;
use App\Filament\Resources\TransactionClassifications\Pages\EditTransactionClassification;
use App\Filament\Resources\TransactionClassifications\Pages\ListTransactionClassifications;
use App\Filament\Resources\TransactionClassifications\Schemas\TransactionClassificationForm;
use App\Filament\Resources\TransactionClassifications\Tables\TransactionClassificationsTable;
use App\Models\TransactionClassification;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionClassificationResource extends Resource
{
    protected static ?string $model = TransactionClassification::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string
    {
        return __('Transaction Classifications');
    }

    public static function getModelLabel(): string
    {
        return __('Transaction Classification');
    }

    public static function getNavigationSort(): int
    {
        return 6;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Transaction Classifications');
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionClassificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionClassificationsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                $query->whereNull('company_id');

                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $query->orWhere('company_id', $selectedCompanyId);
                } else {
                    if ($user) {
                        $userCompanyIds = $user->companies()->pluck('companies.id');
                        if ($userCompanyIds->isNotEmpty()) {
                            $query->orWhereIn('company_id', $userCompanyIds);
                        }
                    }
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
            'index' => ListTransactionClassifications::route('/'),
            'create' => CreateTransactionClassification::route('/create'),
            'edit' => EditTransactionClassification::route('/{record}/edit'),
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



