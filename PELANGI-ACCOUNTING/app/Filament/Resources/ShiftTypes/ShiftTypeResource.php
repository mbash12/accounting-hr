<?php

namespace App\Filament\Resources\ShiftTypes;

use App\Filament\Resources\ShiftTypes\Pages\CreateShiftType;
use App\Filament\Resources\ShiftTypes\Pages\EditShiftType;
use App\Filament\Resources\ShiftTypes\Pages\ListShiftTypes;
use App\Filament\Resources\ShiftTypes\Schemas\ShiftTypeForm;
use App\Filament\Resources\ShiftTypes\Tables\ShiftTypesTable;
use App\Models\ShiftType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShiftTypeResource extends Resource
{
    protected static ?string $model = ShiftType::class;

    protected static ?string $navigationLabel = 'Shift Types';

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    protected static ?int $navigationSort = 12;

    public static function getModelLabel(): string
    {
        return __('Shift Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Shift Types');
    }

    public static function form(Schema $schema): Schema
    {
        return ShiftTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListShiftTypes::route('/'),
            'create' => CreateShiftType::route('/create'),
            'edit'   => EditShiftType::route('/{record}/edit'),
        ];
    }
}
