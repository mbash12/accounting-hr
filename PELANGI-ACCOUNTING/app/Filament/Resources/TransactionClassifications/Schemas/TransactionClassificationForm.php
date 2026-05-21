<?php

namespace App\Filament\Resources\TransactionClassifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionClassificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__("Transaction Classification Information"))
                ->schema([
                    TextInput::make("name")
                        ->label(__("Name"))
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),
                    TextInput::make("code")
                        ->label(__("Code"))
                        ->required()
                        ->maxLength(50)
                        ->unique(
                            table: \App\Models\TransactionClassification::class,
                            column: 'code',
                            ignorable: fn ($record) => $record,
                        )
                        ->rule(function (callable $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $companyId = $get('company_id');
                                if (empty($companyId)) {
                                    $companyId = session('selected_company_id');
                                }

                                if (empty($companyId) || $value === null || $value === '') {
                                    return;
                                }

                                $record = request()->route('record') ? \App\Models\TransactionClassification::find(request()->route('record')) : null;

                                $query = \App\Models\TransactionClassification::where('code', $value)
                                    ->where('company_id', $companyId);

                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }

                                if ($query->exists()) {
                                    $fail(__('The :attribute code already exists for this company.', ['attribute' => __('Code')]));
                                }
                            };
                        }),
                    Textarea::make("description")
                        ->label(__("Description"))
                        ->rows(3)
                        ->columnSpanFull(),
                    Select::make("classification_type")
                        ->label(__("Classification Type"))
                        ->required()
                        ->options([
                            'operating' => __('Operating'),
                            'investing' => __('Investing'),
                            'financing' => __('Financing'),
                            'non_operating' => __('Non Operating'),
                        ])
                        ->searchable(),
                    Select::make("tax_impact")
                        ->label(__("Tax Impact"))
                        ->options([
                            'taxable' => __('Taxable'),
                            'exempt' => __('Exempt'),
                            'zero_rated' => __('Zero Rated'),
                            'out_of_scope' => __('Out of Scope'),
                        ])
                        ->nullable()
                        ->searchable(),
                    TextInput::make("reporting_category")
                        ->label(__("Reporting Category"))
                        ->maxLength(100)
                        ->nullable(),
                    Toggle::make("is_active")
                        ->label(__("Active"))
                        ->default(true)
                        ->required(),
                ])
                ->columns(2),

            Section::make(__("Account Mapping"))
                ->schema([
                    Select::make("company_id")
                        ->relationship(
                            name: 'company',
                            titleAttribute: 'name',
                            modifyQueryUsing: function ($query) {
                                $user = auth()->user();
                                if ($user) {
                                    $userCompanyIds = $user->companies()->pluck('companies.id');
                                    if ($userCompanyIds->isNotEmpty()) {
                                        $query->whereIn('companies.id', $userCompanyIds);
                                    } else {
                                        $query->whereRaw('1 = 0');
                                    }
                                }
                            }
                        )
                        ->default(function () {
                            $selectedCompanyId = session('selected_company_id');
                            $user = auth()->user();
                            if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                                if ($user && $user->companies()->where('companies.id', $selectedCompanyId)->exists()) {
                                    return $selectedCompanyId;
                                }
                            }
                            return null;
                        })
                        ->hidden(function () {
                            $user = auth()->user();
                            return $user && $user->companies()->count() === 0;
                        })
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('default_account_id', null);
                        })
                        ->label(__("Company")),
                    Select::make("default_account_id")
                        ->label(__("Default Account"))
                        ->getOptionLabelUsing(function ($value) {
                            $account = \App\Models\Account::find($value);
                            return $account ? "{$account->code} - {$account->name}" : $value;
                        })
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search, callable $get) {
                            $liveCompany = $get('company_id');
                            $globalCompanyId = session('selected_company_id');
                            $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                            $query = \App\Models\Account::where('is_header', false)
                                ->where('is_active', true)
                                ->where(function ($query) use ($search) {
                                    $query->where('code', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%");
                                });

                            if ($companyId) {
                                $query->where(function ($q) use ($companyId) {
                                    $q->where('company_id', $companyId)
                                        ->orWhereNull('company_id');
                                });
                            }

                            return $query->orderBy('code')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                        })
                        ->options(function (callable $get) {
                            $liveCompany = $get('company_id');
                            $globalCompanyId = session('selected_company_id');
                            $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                            $query = \App\Models\Account::where('is_header', false)
                                ->where('is_active', true);

                            if ($companyId) {
                                $query->where(function ($q) use ($companyId) {
                                    $q->where('company_id', $companyId)
                                        ->orWhereNull('company_id');
                                });
                            }

                            return $query->orderBy('code')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                        })
                        ->live()
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}

