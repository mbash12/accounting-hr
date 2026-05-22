<?php

namespace App\Filament\Resources\JournalEntries;

use App\Filament\Resources\JournalEntries\Pages\CreateJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\EditJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\ListJournalEntries;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryForm;
use App\Filament\Resources\JournalEntries\Tables\JournalEntriesTable;
use App\Models\JournalEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static UnitEnum|string|null $navigationGroup = 'General Ledger';

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public static function getNavigationLabel(): string
    {
        return __('General Journal Transactions');
    }

    public static function getModelLabel(): string
    {
        return __('General Journal Transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('General Journal Transactions');
    }

    // Translations handled in methods below

    public static function form(Schema $schema): Schema
    {
        return JournalEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalEntriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->whereNull('sub_module')
                    ->whereNull('reference_type');
            })
            ->when(
                session('selected_company_id') && session('selected_company_id') !== 'all',
                fn(Builder $query) => $query->where('company_id', session('selected_company_id'))
            );
        // When 'all' is selected or no company selected, show all records (both global and company-specific)
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
            'index' => ListJournalEntries::route('/'),
            'create' => CreateJournalEntry::route('/create'),
            'edit' => EditJournalEntry::route('/{record}/edit'),
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
