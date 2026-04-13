<?php

namespace App\Filament\Resources\Expeditions;

use App\Filament\Resources\Expeditions\Pages\CreateExpedition;
use App\Filament\Resources\Expeditions\Pages\EditExpedition;
use App\Filament\Resources\Expeditions\Pages\ListExpeditions;
use App\Filament\Resources\Expeditions\Schemas\ExpeditionForm;
use App\Filament\Resources\Expeditions\Tables\ExpeditionsTable;
use App\Models\Expedition;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpeditionResource extends Resource
{
    protected static ?string $model = Expedition::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function getNavigationLabel(): string
    {
        return __('Expeditions');
    }

    public static function getModelLabel(): string
    {
        return __('Expedition');
    }

    public static function getNavigationSort(): int
    {
        return 7;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Expeditions');
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function form(Schema $schema): Schema
    {
        return ExpeditionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpeditionsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's expeditions
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only expeditions from user's assigned companies
                    if ($user) {
                        $userCompanyIds = $user->companies()->pluck('companies.id');
                        if ($userCompanyIds->isNotEmpty()) {
                            $query->whereIn('company_id', $userCompanyIds);
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
            'index' => ListExpeditions::route('/'),
            'create' => CreateExpedition::route('/create'),
            'edit' => EditExpedition::route('/{record}/edit'),
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
