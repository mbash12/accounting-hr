<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Tables\DepartmentsTable;
use App\Models\Department;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Department');
    }

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('Department');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Department');
    }

    public static function form(Schema $schema): Schema

    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $selectedCompanyId = session('selected_company_id');
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function ($query) use ($selectedCompanyId, $user) {
                if ($selectedCompanyId) {
                    // When specific company selected, show that company's departments
                    $query->where('company_id', $selectedCompanyId);
                } else {
                    // When no company selected, show only departments from user's assigned companies
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
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
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
