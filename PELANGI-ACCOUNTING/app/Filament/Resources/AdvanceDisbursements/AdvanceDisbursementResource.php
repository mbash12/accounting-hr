<?php

namespace App\Filament\Resources\AdvanceDisbursements;

use App\Filament\Resources\AdvanceDisbursements\Pages\CreateAdvanceDisbursement;
use App\Filament\Resources\AdvanceDisbursements\Pages\EditAdvanceDisbursement;
use App\Filament\Resources\AdvanceDisbursements\Pages\ListAdvanceDisbursements;
use App\Filament\Resources\AdvanceDisbursements\Schemas\AdvanceDisbursementForm;
use App\Filament\Resources\AdvanceDisbursements\Tables\AdvanceDisbursementsTable;
use App\Models\AdvanceDisbursement;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvanceDisbursementResource extends Resource
{
    protected static ?string $model = AdvanceDisbursement::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Cash & Bank');
    }

    public static function getNavigationLabel(): string
    {
        return __('Cash Advances');
    }

    public static function getModelLabel(): string
    {
        return __('Cash Advance');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Cash Advances');
    }

    public static function form(Schema $schema): Schema
    {
        return AdvanceDisbursementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvanceDisbursementsTable::configure($table);
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
            'index' => ListAdvanceDisbursements::route('/'),
            'create' => CreateAdvanceDisbursement::route('/create'),
            'edit' => EditAdvanceDisbursement::route('/{record}/edit'),
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
