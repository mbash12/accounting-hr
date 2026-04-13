<?php

namespace App\Filament\Resources\BusinessTypes;

use App\Filament\Resources\BusinessTypes\Pages\CreateBusinessType;
use App\Filament\Resources\BusinessTypes\Pages\EditBusinessType;
use App\Filament\Resources\BusinessTypes\Pages\ListBusinessTypes;

use App\Filament\Resources\BusinessTypes\Schemas\BusinessTypeForm;
use App\Filament\Resources\BusinessTypes\Tables\BusinessTypesTable;
use App\Models\BusinessType;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessTypeResource extends Resource
{
    protected static ?string $model = BusinessType::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Entity Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Business Types');
    }

    public static function getModelLabel(): string
    {
        return __('Business Type');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Business Types');
    }

    public static function form(Schema $schema): Schema
    {
        return BusinessTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessTypesTable::configure($table);
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
            'index' => ListBusinessTypes::route('/'),
            'create' => CreateBusinessType::route('/create'),

            'edit' => EditBusinessType::route('/{record}/edit'),
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
