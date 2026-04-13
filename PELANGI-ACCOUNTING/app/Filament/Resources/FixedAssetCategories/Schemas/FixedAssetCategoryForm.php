<?php

namespace App\Filament\Resources\FixedAssetCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FixedAssetCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informasi Kategori'))
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(200)
                            ->label(__('Nama Kategori'))
                            ->columnSpanFull(),
                        app(\App\Models\FixedAssetCategory::class)->getCodeField()
                            ->label(__('Kode Kategori'))
                            ->helperText(__('Kode unik untuk kategori aset tetap'))
                            ->columnSpanFull()
                            ->unique(
                                \App\Models\FixedAssetCategory::class,
                                'code',
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule) {
                                    // Get the company_id from the form
                                    $companyId = session('selected_company_id');
                                    if ($companyId) {
                                        $rule->where('company_id', $companyId);
                                    }
                                    return $rule;
                                },
                            ),
                        Select::make('depreciation_method')
                            ->options([
                                'straight_line' => __('Garis Lurus'),
                                'declining_balance' => __('Saldo Menurun'),
                                'double_declining' => __('Saldo Menurun Ganda'),
                                'sum_of_years' => __('Jumlah Angka Tahun'),
                                'units_of_production' => __('Satuan Hasil Produksi'),
                            ])
                            ->required()
                            ->label(__('Metode Penyusutan')),
                        TextInput::make('useful_life')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label(__('Masa Manfaat'))
                            ->suffix(__('tahun')),
                        Select::make('company_id')
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
                                            // If user has no assigned companies, return no results
                                            $query->whereRaw('1 = 0');
                                        }
                                    }
                                }
                            )
                            ->default(function () {
                                $selectedCompanyId = session('selected_company_id');
                                $user = auth()->user();
                                if ($selectedCompanyId) {
                                    // Verify user has access to this company
                                    if ($user && $user->companies()->where('companies.id', $selectedCompanyId)->exists()) {
                                        return $selectedCompanyId;
                                    }
                                }
                                return null;
                            })
                            ->hidden()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Clear all account fields when company changes to prevent mismatch
                                $set('asset_account_id', null);
                                $set('accumulated_depreciation_account_id', null);
                                $set('depreciation_account_id', null);
                                $set('sales_account_id', null);
                            })
                            ->label(__('Company'))
                            ->helperText(function () {
                                $selectedCompanyId = session('selected_company_id');
                                if (!$selectedCompanyId || $selectedCompanyId === 'all') {
                                    return __('Required: Select which company this category belongs to');
                                }
                                return '';
                            }),
                    Toggle::make('is_active')
                        ->required()
                        ->default(true)
                        ->label(__('Active')),
                ])
                ->columns(2),

                Section::make(__('Account Configuration'))
                    ->schema([
                        Select::make('asset_account_id')
                            ->options(function (callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }
                                
                                return $query->orderBy('code')->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }

                                $query->where(function ($q) use ($search) {
                                    $q->where('code', 'ilike', "%{$search}%")
                                      ->orWhere('name', 'ilike', "%{$search}%");
                                });

                                return $query->orderBy('code')->limit(50)->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->live()
                            ->label(__('Akun Aset'))
                            ->required()
                            ->columnSpanFull()
                            ->helperText(__('Akun untuk mencatat nilai aset')),
                        Select::make('accumulated_depreciation_account_id')
                            ->options(function (callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }
                                
                                return $query->orderBy('code')->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }

                                $query->where(function ($q) use ($search) {
                                    $q->where('code', 'ilike', "%{$search}%")
                                      ->orWhere('name', 'ilike', "%{$search}%");
                                });

                                return $query->orderBy('code')->limit(50)->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->live()
                            ->label(__('Akun Akumulasi Penyusutan'))
                            ->required()
                            ->columnSpanFull()
                            ->helperText(__('Akun untuk melacak akumulasi penyusutan')),
                        Select::make('depreciation_account_id')
                            ->options(function (callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }
                                
                                return $query->orderBy('code')->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }

                                $query->where(function ($q) use ($search) {
                                    $q->where('code', 'ilike', "%{$search}%")
                                      ->orWhere('name', 'ilike', "%{$search}%");
                                });

                                return $query->orderBy('code')->limit(50)->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->live()
                            ->label(__('Akun Beban Penyusutan'))
                            ->required()
                            ->columnSpanFull()
                            ->helperText(__('Akun untuk beban penyusutan')),
                        Select::make('sales_account_id')
                            ->options(function (callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }
                                
                                return $query->orderBy('code')->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $liveCompany = $get('company_id');
                                $globalCompanyId = session('selected_company_id');
                                $companyId = $liveCompany ?: ($globalCompanyId && $globalCompanyId !== 'all' ? $globalCompanyId : null);

                                $query = \App\Models\Account::where('is_header', false)->where('is_active', true);
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }

                                $query->where(function ($q) use ($search) {
                                    $q->where('code', 'ilike', "%{$search}%")
                                      ->orWhere('name', 'ilike', "%{$search}%");
                                });

                                return $query->orderBy('code')->limit(50)->get()->mapWithKeys(fn ($account) => [
                                    $account->id => "{$account->code} - {$account->name}",
                                ]);
                            })
                            ->live()
                            ->label(__('Akun Penjualan'))
                            ->required()
                            ->columnSpanFull()
                            ->helperText(__('Akun untuk keuntungan/kerugian pelepasan aset')),
                    ]),
            ]);
    }
}
