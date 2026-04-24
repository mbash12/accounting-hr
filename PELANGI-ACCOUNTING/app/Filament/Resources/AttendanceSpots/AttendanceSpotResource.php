<?php

namespace App\Filament\Resources\AttendanceSpots;

use App\Filament\Resources\AttendanceSpots\Pages\CreateAttendanceSpot;
use App\Filament\Resources\AttendanceSpots\Pages\EditAttendanceSpot;
use App\Filament\Resources\AttendanceSpots\Pages\ListAttendanceSpots;
use App\Filament\Resources\AttendanceSpots\Schemas\AttendanceSpotForm;
use App\Filament\Resources\AttendanceSpots\Tables\AttendanceSpotsTable;
use App\Models\AttendanceSpot;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceSpotResource extends Resource
{
    protected static ?string $model = AttendanceSpot::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Spot Absensi');
    }

    protected static ?int $navigationSort = 8;

    public static function getModelLabel(): string
    {
        return __('Spot Absensi');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Spot Absensi');
    }

    public static function form(Schema $schema): Schema
    {
        return AttendanceSpotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceSpotsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                } elseif ($user) {
                    $userCompanyIds = $user->companies()->pluck('companies.id');
                    if ($userCompanyIds->isNotEmpty()) {
                        $query->whereIn('company_id', $userCompanyIds);
                    }
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceSpots::route('/'),
            'create' => CreateAttendanceSpot::route('/create'),
            'edit' => EditAttendanceSpot::route('/{record}/edit'),
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
