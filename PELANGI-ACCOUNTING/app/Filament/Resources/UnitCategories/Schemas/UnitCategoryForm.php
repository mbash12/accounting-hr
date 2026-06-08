<?php

namespace App\Filament\Resources\UnitCategories\Schemas;

use App\Models\Unit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UnitCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('UOM Category Information'))
                    ->schema([
                        TextInput::make('code')
                            ->disabled()
                            ->required()
                            ->maxLength(50)
                            ->label(__('Code')),
                        TextInput::make('name')
                            ->disabled()
                            ->required()
                            ->maxLength(100)
                            ->label(__('Category Name')),
                        Select::make('base_unit_id')
                            ->disabled()
                            ->label(__('Base Unit'))
                            ->options(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $q = Unit::query();
                                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                    $q->where('company_id', $selectedCompanyId);
                                }
                                return $q->orderBy('name')->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('company_id')
                            ->relationship(
                                name: 'company',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query) {
                                    $selectedCompanyId = session('selected_company_id');
                                    if ($selectedCompanyId) {
                                        $query->where('id', $selectedCompanyId);
                                    }
                                    return $query;
                                }
                            )
                            ->default(function () {
                                return session('selected_company_id');
                            })
                            ->hidden()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('Company')),
                        Select::make('created_by_user_id')
                            ->relationship('createdByUser', 'name')
                            ->default(fn () => auth()->id())
                            ->disabled()
                            ->hidden()
                            ->required()
                            ->label(__('Created By')),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
