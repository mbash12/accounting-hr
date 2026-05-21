<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use App\Models\SalaryComponent;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalaryComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'salaryComponents';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('Salary Components');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('salary_component_id')
                    ->label(__('Salary Components'))
                    ->options(function () {
                        $companyId = session('selected_company_id');
                        return SalaryComponent::query()
                            ->when($companyId && $companyId !== 'all', fn ($q) => $q->where('company_id', $companyId))
                            ->where('is_active', true)
                            ->orderBy('type')
                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(fn ($c) => [
                                $c->id => "[{$c->code}] {$c->name} (" . ($c->type === 'allowance' ? __('Allowance') : __('Deduction')) . ")",
                            ]);
                    })
                    ->searchable()
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('salaryComponent.name')
            ->columns([
                TextColumn::make('salaryComponent.code')
                    ->label(__('Code'))
                    ->sortable(),
                TextColumn::make('salaryComponent.name')
                    ->label(__('Component Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('salaryComponent.type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'allowance' => __('Allowance'),
                        'deduction' => __('Deduction'),
                        default     => $state,
                    })
                    ->color(fn (string $state): string => $state === 'allowance' ? 'success' : 'danger'),
                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
