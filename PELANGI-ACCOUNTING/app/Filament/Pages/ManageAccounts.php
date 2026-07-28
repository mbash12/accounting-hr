<?php

namespace App\Filament\Pages;

use App\Filament\Actions\ExportAccountsAction;
use App\Filament\Actions\ImportAccountsAction;
use App\Models\Account;
use App\Models\AccountTemplate;
use App\Models\FixedAssetCategory;
use App\Models\FixedAssetCategoryTemplate;
use App\Models\OpeningBalance;
use App\Models\Tax;
use App\Models\TaxTemplate;
use App\Services\DataCleanupService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use RuntimeException;
use UnitEnum;

class ManageAccounts extends Page
{
    use HasPageShield;
    public $search = '';
    public array $filteredAccountIds = [];
    
    protected static ?string $navigationLabel = 'Chart of Accounts';
    
    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $title = 'Chart of Accounts';
    
    protected string $view = 'filament.pages.manage-accounts';
    
    public static function getNavigationLabel(): string
    {
        return __('Chart of Accounts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public function getTitle(): string
    {
        return __('Chart of Accounts');
    }
    
    public function getAccounts()
    {
        $query = Account::with('children', 'parent')
            ->whereNull('parent_id')
            ->when(
                session('selected_company_id') && session('selected_company_id') !== 'all',
                fn($query) => $query->where('company_id', session('selected_company_id'))
            );
            
        // Apply search filter if search term is provided
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            
            // Get all matching account IDs (including children)
            $matchingAccountIds = $this->getMatchingAccountIds($searchTerm);
            
            if (!empty($matchingAccountIds)) {
                // Get all parent accounts of matching accounts
                $parentIds = $this->getAllParentIds($matchingAccountIds);
                $allIds = array_unique(array_merge($matchingAccountIds, $parentIds));
                
                $this->filteredAccountIds = $allIds;

                // Get top-level accounts that either match directly or have matching descendants
                $query->where(function ($q) use ($allIds) {
                    $q->whereIn('id', $allIds)
                      ->orWhereHas('children', function ($subQ) use ($allIds) {
                          $subQ->whereIn('id', $allIds);
                      });
                });
            } else {
                // If no matches, return empty result
                $this->filteredAccountIds = [];
                $query->whereRaw('1 = 0');
            }
        } else {
            $this->filteredAccountIds = [];
        }
        
        return $query->orderBy('code')->get();
    }
    
    private function getMatchingAccountIds(string $searchTerm): array
    {
        $companyId = session('selected_company_id');
        $searchTerm = strtolower($searchTerm);
        
        $query = Account::where(function ($q) use ($searchTerm) {
            $q->whereRaw('LOWER(code) LIKE ?', [$searchTerm])
              ->orWhereRaw('LOWER(name) LIKE ?', [$searchTerm])
              ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm]);
        });
        
        if ($companyId && $companyId !== 'all') {
            $query->where('company_id', $companyId);
        }
        
        return $query->pluck('id')->toArray();
    }
    
    private function getAllParentIds(array $accountIds): array
    {
        $parentIds = [];
        $processedIds = [];
        
        while (!empty($accountIds)) {
            $currentIds = array_diff($accountIds, $processedIds);
            if (empty($currentIds)) break;
            
            $parents = Account::whereIn('id', $currentIds)
                ->whereNotNull('parent_id')
                ->pluck('parent_id')
                ->toArray();
            
            $parentIds = array_merge($parentIds, $parents);
            $processedIds = array_merge($processedIds, $currentIds);
            $accountIds = $parents;
        }
        
        return array_unique($parentIds);
    }
    
    public function updatedSearch()
    {
        // This method is called automatically when the search property is updated
        // It will trigger a re-render of the component with filtered results
    }
    
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('expandAll')
                ->label(__('Expand All'))
                ->icon('heroicon-o-chevron-down')
                ->color('gray')
                ->extraAttributes(['class' => 'expand-all-btn'])
                ->view('filament.pages.partials.expand-all-action'),
            \Filament\Actions\Action::make('collapseAll')
                ->label(__('Collapse All'))
                ->icon('heroicon-o-chevron-up')
                ->color('gray')
                ->extraAttributes(['class' => 'collapse-all-btn'])
                ->view('filament.pages.partials.collapse-all-action'),
            ImportAccountsAction::make(),
            ExportAccountsAction::make(),
            $this->openingBalanceAction(),
            $this->clearChartOfAccountsAction(),
        ];
    }

    public function clearChartOfAccountsAction(): Action
    {
        return Action::make('clearChartOfAccounts')
            ->label(__('Clear COA'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->visible(function () {
                $selectedCompanyId = session('selected_company_id');

                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    return Account::where('company_id', $selectedCompanyId)->exists();
                }

                return false;
            })
            ->requiresConfirmation()
            ->modalHeading(__('Clear Chart of Accounts'))
            ->modalDescription(__('This deletes all accounts for the selected company, plus account mappings, opening balances, taxes, and fixed asset categories. Blocked if journal entries exist. This cannot be undone.'))
            ->form(function () {
                $companyId = session('selected_company_id');
                $companyName = ($companyId && $companyId !== 'all')
                    ? \App\Models\Company::query()->whereKey($companyId)->value('name')
                    : '';

                return [
                    TextInput::make('company_name')
                        ->label(__('Type the company name to confirm'))
                        ->helperText(__('Expected: :name', ['name' => $companyName]))
                        ->required()
                        ->rules([
                            fn () => function (string $attribute, $value, $fail) use ($companyName) {
                                if ((string) $value !== (string) $companyName) {
                                    $fail(__('Company name does not match.'));
                                }
                            },
                        ]),
                    TextInput::make('confirmation')
                        ->label(__('Type CLEAR to confirm'))
                        ->required()
                        ->rules(['in:CLEAR']),
                ];
            })
            ->action(function () {
                $selectedCompanyId = session('selected_company_id');

                if (!$selectedCompanyId || $selectedCompanyId === 'all') {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body(__('Please select a company first'))
                        ->send();

                    return;
                }

                try {
                    $result = app(DataCleanupService::class)->clear(
                        [DataCleanupService::DATASET_CHART_OF_ACCOUNTS],
                        (int) $selectedCompanyId,
                        DataCleanupService::MODE_CASCADE
                    );

                    Notification::make()
                        ->success()
                        ->title(__('Chart of Accounts cleared'))
                        ->body(__('Removed :count account(s).', [
                            'count' => $result['deleted'][DataCleanupService::DATASET_CHART_OF_ACCOUNTS] ?? 0,
                        ]))
                        ->send();

                    $this->dispatch('$refresh');
                } catch (RuntimeException $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Cannot clear COA'))
                        ->body($e->getMessage())
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body($e->getMessage())
                        ->send();
                }
            });
    }
    
    protected function getActions(): array
    {
        return [
            $this->editAction(),
            $this->addChildAction(),
            $this->deleteAction(),
        ];
    }
    
    public function generateFromTemplateAction(): Action
    {
        return Action::make('generateFromTemplate')
            ->label(__('Generate Chart of Accounts'))
            ->icon('heroicon-o-document-duplicate')
            ->color('primary')
            ->visible(function () {
                // Get selected company ID
                $selectedCompanyId = session('selected_company_id');
                
                // Only show when a specific company is selected (not 'all') and there are no accounts for that company
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $hasAccounts = Account::where('company_id', $selectedCompanyId)->exists();
                    return !$hasAccounts;
                }
                
                return false;
            })
            ->form([
                Select::make('template_name')
                    ->label(__('Account Template'))
                    ->required()
                    ->options(function () {
                        return AccountTemplate::getTemplateNames()
                            ->mapWithKeys(fn ($template) => [$template => $template])
                            ->toArray();
                    })
                    ->default('Standard Indonesian COA')
                    ->searchable(),
                Select::make('tax_template_name')
                    ->label(__('Tax Template'))
                    ->options(function () {
                        return TaxTemplate::getTemplateNames()
                            ->mapWithKeys(fn ($template) => [$template => $template])
                            ->toArray();
                    })
                    ->default('Standard Indonesian Taxes')
                    ->searchable(),
            ])
            ->action(function (array $data) {
                $selectedCompanyId = session('selected_company_id');
                if (!$selectedCompanyId || $selectedCompanyId === 'all') {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body(__('Please select a company first'))
                        ->send();
                    return;
                }
                
                try {
                    $this->importAccountTemplate($data['template_name'], $selectedCompanyId);

                    // Import taxes if template is selected
                    if (!empty($data['tax_template_name'])) {
                        $this->importTaxTemplate($data['tax_template_name'], $selectedCompanyId);
                    }

                    Notification::make()
                        ->success()
                        ->title(__('Success'))
                        ->body(__('Chart of Accounts and Taxes imported successfully'))
                        ->send();
                    
                    $this->dispatch('$refresh');
                    
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body(__('Failed to import templates: ') . $e->getMessage())
                        ->send();
                }
            })
            ->modalWidth('2xl');
    }

    public function openingBalanceAction(): Action
    {
        return Action::make('openingBalance')
            ->label(__('Manage Opening Balances'))
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->visible(function () {
                // Get selected company ID
                $selectedCompanyId = session('selected_company_id');

                // Only show when a specific company is selected and there are existing accounts
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $hasAccounts = Account::where('company_id', $selectedCompanyId)->exists();
                    return $hasAccounts;
                }

                return false;
            })
            ->url(function () {
                return ManageOpeningBalances::getUrl();
            });
    }

    private function importAccountTemplate(string $templateName, int $companyId): void
    {
        DB::beginTransaction();
        
        try {
            $templates = AccountTemplate::getByTemplate($templateName);
            
            if ($templates->isEmpty()) {
                throw new \Exception("Template '{$templateName}' not found");
            }
            
            // Check if company already has accounts
            $existingAccountCount = Account::where('company_id', $companyId)->count();
            if ($existingAccountCount > 0) {
                throw new \Exception("Company already has {$existingAccountCount} accounts. Import aborted to prevent duplicates.");
            }
            
            $codeToIdMap = [];
            
            // Create accounts from template
            foreach ($templates as $template) {
                $account = Account::create([
                    'code' => $template->code,
                    'name' => $template->name,
                    'description' => $template->description,
                    'classification_type' => $template->classification_type,
                    'account_type' => $template->account_type,
                    'is_header' => $template->is_header,
                    'is_cash_bank' => $template->is_cash_bank,
                    'is_active' => $template->is_active,
                    'cash_flow' => $template->cash_flow,
                    'level' => $template->level,
                    'opening_balance' => 0,
                    'current_balance' => 0,
                    'parent_id' => null, // Will be set in second pass
                    'company_id' => $companyId,
                    'created_by_user_id' => auth()->id(),
                ]);
                
                $codeToIdMap[$template->code] = $account->id;
            }
            
            // Update parent relationships
            foreach ($templates as $template) {
                if ($template->parent_code && isset($codeToIdMap[$template->parent_code])) {
                    Account::where('code', $template->code)
                        ->where('company_id', $companyId)
                        ->update(['parent_id' => $codeToIdMap[$template->parent_code]]);
                }
            }

            // No parent = classification root; children point classification_id to root
            $accounts = Account::query()
                ->where('company_id', $companyId)
                ->whereIn('id', array_values($codeToIdMap))
                ->get()
                ->keyBy('id');

            foreach ($accounts as $account) {
                $root = $account;
                $guard = 0;
                while ($root->parent_id !== null && $guard < 50) {
                    $parent = $accounts->get($root->parent_id);
                    if (!$parent) {
                        break;
                    }
                    $root = $parent;
                    $guard++;
                }

                $account->update([
                    'classification_id' => $root->id === $account->id ? null : $root->id,
                ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    
    private function importTaxTemplate(string $templateName, int $companyId): void
    {
        DB::beginTransaction();
        
        try {
            $templates = TaxTemplate::getByTemplate($templateName);
            
            if ($templates->isEmpty()) {
                throw new \Exception("Tax template '{$templateName}' not found");
            }
            
            // Check if company already has taxes
            $existingTaxCount = Tax::where('company_id', $companyId)->count();
            if ($existingTaxCount > 0) {
                throw new \Exception("Company already has {$existingTaxCount} taxes. Import aborted to prevent duplicates.");
            }
            
            foreach ($templates as $template) {
                Tax::create([
                    'name' => $template->name,
                    'code' => $template->code,
                    'tax_percentage' => $template->tax_percentage,
                    'tax_type' => $template->tax_type,
                    'is_purchase_tax' => $template->is_purchase_tax,
                    'is_sales_tax' => $template->is_sales_tax,
                    'effective_date' => $template->effective_date,
                    'expiry_date' => $template->expiry_date,
                    'compound_tax' => $template->compound_tax,
                    'is_active' => $template->is_active,
                    'purchase_account_id' => $this->findAccountIdByCode($template->purchase_account_code, $companyId),
                    'sales_account_id' => $this->findAccountIdByCode($template->sales_account_code, $companyId),
                    'company_id' => $companyId,
                    'created_by_user_id' => auth()->id(),
                ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    private function findAccountIdByCode(?string $code, int $companyId): ?int
    {
        if (!$code) return null;

        return Account::where('code', $code)
            ->where('company_id', $companyId)
            ->first()?->id;
    }
    
    public function editAction(): Action
    {
        return Action::make('edit')
            ->modalHeading(__('Edit Account'))
            ->form($this->getAccountForm())
            ->fillForm(function (array $arguments) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::with('company')->find($accountId);
                    if ($account) {
                        $data = $account->toArray();

                        // Find the top-level classification (shortest digit-length root)
                        $topParent = $account;
                        while ($topParent->parent_id !== null) {
                            $parent = Account::find($topParent->parent_id);
                            if (!$parent) {
                                break;
                            }
                            $topParent = $parent;
                        }
                        $data['classification_id'] = $topParent->id;

                        return $data;
                    }
                }
                return [];
            })
            ->model(Account::class)
            ->action(function (array $arguments, array $data) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::find($accountId);
                    if ($account) {
                        // Classification roots (min digit length) only allow renaming
                        $isTopLevel = $account->isClassificationRoot();
                        
                        if ($isTopLevel) {
                            // Keep classification roots restricted to label/header status only.
                            $account->update([
                                'name' => $data['name'],
                                'is_header' => (bool) ($data['is_header'] ?? $account->is_header),
                            ]);
                        } else {
                            // Check if code is unique within the same company (excluding current record)
                            if (isset($data['code'])) {
                                $account = Account::find($accountId);
                                if ($account) {
                                    $existingAccount = Account::where('code', $data['code'])
                                        ->where('company_id', $account->company_id)
                                        ->where('id', '!=', $accountId)
                                        ->first();

                                    if ($existingAccount) {
                                        Notification::make()
                                            ->danger()
                                            ->title(__('Validation Error'))
                                            ->body(__('The code has already been taken for this company.'))
                                            ->send();
                                        return;
                                    }
                                }
                            }

                            $data = Account::normalizeOtherIncomeExpenseAttributes($data);
                            $parent = isset($data['parent_id'])
                                ? Account::find($data['parent_id'])
                                : $account->parent;
                            $hierarchyError = Account::validateOtherIncomeExpenseHierarchy(
                                (string) ($data['account_type'] ?? $account->account_type),
                                $parent
                            );
                            if ($hierarchyError) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('Validation Error'))
                                    ->body($hierarchyError)
                                    ->send();
                                return;
                            }

                            $account->update($data);
                        }

                        Notification::make()
                            ->success()
                            ->title(__('Account updated successfully'))
                            ->send();

                        $this->dispatch('$refresh');
                    }
                }
            });
    }
    
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete Account'))
            ->modalDescription(__('Are you sure you want to delete this account? This action cannot be undone.'))
            ->color('danger')
            ->action(function (array $arguments) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::find($accountId);
                    if ($account) {
                        // Check if account has children
                        if ($account->children()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title(__('Cannot delete account'))
                                ->body(__('This account has child accounts. Please delete or move them first.'))
                                ->send();
                            return;
                        }
                        
                        $account->delete();
                        
                        Notification::make()
                            ->success()
                            ->title(__('Account deleted successfully'))
                            ->send();
                        
                        $this->dispatch('$refresh');
                    }
                }
            });
    }
    
    public function addChildAction(): Action
    {
        return Action::make('addChild')
            ->label(__('Add Child'))
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(function (array $arguments) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::find($accountId);
                    return $account ? __('Add Child Account to: ') . $account->name : __('Add Child Account');
                }
                return __('Add Child Account');
            })
            ->form(function (array $arguments) {
                $accountId = $arguments['record'] ?? null;
                return $this->getAccountForm($accountId);
            })
            ->fillForm(function (array $arguments) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::with('company')->find($accountId);
                    if ($account) {
                        // Find the top-level classification (shortest digit-length root)
                        $topParent = $account;
                        while ($topParent->parent_id !== null) {
                            $parent = Account::find($topParent->parent_id);
                            if (!$parent) {
                                break;
                            }
                            $topParent = $parent;
                        }

                        return [
                            'classification_id' => $topParent->id,
                            'parent_id' => $accountId,
                            'classification_type' => $account->classification_type,
                        ];
                    }
                }
                return [];
            })
            ->action(function (array $arguments, array $data) {
                $accountId = $arguments['record'] ?? null;
                if ($accountId) {
                    $account = Account::find($accountId);
                    if ($account) {
                        $data['parent_id'] = $account->id;
                        $data['level'] = $account->level + 1;
                        $data['company_id'] = $account->company_id;
                        $data = Account::normalizeOtherIncomeExpenseAttributes($data);

                        $hierarchyError = Account::validateOtherIncomeExpenseHierarchy(
                            (string) ($data['account_type'] ?? ''),
                            $account
                        );
                        if ($hierarchyError) {
                            Notification::make()
                                ->danger()
                                ->title(__('Validation Error'))
                                ->body($hierarchyError)
                                ->send();
                            return;
                        }

                        // Check if code is unique within the same company when creating
                        if (isset($data['code'])) {
                            $existingAccount = Account::where('code', $data['code'])
                                ->where('company_id', $account->company_id)
                                ->first();

                            if ($existingAccount) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('Validation Error'))
                                    ->body(__('The code has already been taken for this company.'))
                                    ->send();
                                return;
                            }
                        }

                        Account::create($data);

                        Notification::make()
                            ->success()
                            ->title(__('Child account created successfully'))
                            ->send();

                        $this->dispatch('$refresh');
                    }
                }
            })
            ->model(Account::class)
            ->modalWidth('2xl');
    }
    
    public function getAccountForm(?int $parentId = null): array
    {
        return [
            Select::make('classification_id')
                ->label(__('Classification'))
                ->required()
                ->options(function () {
                    // Classification roots = top-level accounts (parent_id is null)
                    $selectedCompanyId = session('selected_company_id');
                    if (!$selectedCompanyId || $selectedCompanyId === 'all') {
                        return [];
                    }

                    return Account::classificationRootsForCompany((int) $selectedCompanyId)
                        ->mapWithKeys(fn ($account) => [$account->id => $account->code . ' - ' . $account->name])
                        ->toArray();
                })
                ->reactive()
                ->disabled()
                ->hidden(function (callable $get) {
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);

                        return $account && $account->isClassificationRoot();
                    }

                    return false;
                }),
            
            Select::make('parent_id')
                ->label(__('Subclassification'))
                ->options(function (callable $get) {
                    $classificationId = $get('classification_id');
                    if (!$classificationId) {
                        return [];
                    }
                    // Get direct children of the selected classification (subclassifications)
                    $query = Account::query()
                        ->where('parent_id', $classificationId);

                    $selectedCompanyId = session('selected_company_id');
                    if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                        $query->where('company_id', $selectedCompanyId);
                    }

                    return $query
                        ->orderBy('code')
                        ->get()
                        ->mapWithKeys(fn ($account) => [$account->id => $account->code . ' - ' . $account->name])
                        ->toArray();
                })
                ->searchable()
                ->nullable()
                ->default($parentId)
                ->hidden(true),
            
            TextInput::make('code')
                ->label(__('Code'))
                ->required()
                ->maxLength(50)
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        if (empty($value) || empty($get('company_id'))) {
                            return; // Skip validation if no value or company_id
                        }

                        $companyId = $get('company_id');
                        $accountId = $get('id'); // For editing existing records

                        $query = Account::where('code', $value)
                            ->where('company_id', $companyId);

                        if ($accountId) {
                            // When editing, exclude the current record from validation
                            $query->where('id', '!=', $accountId);
                        }

                        if ($query->exists()) {
                            $fail(__('The :attribute code already exists for this company.', ['attribute' => __('Code')]));
                        }
                    };
                })
                ->hidden(function (callable $get) {
                    // Check if current form data indicates a top-level account
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);
                        return $account && $account->isClassificationRoot();
                    }
                    return false;
                }),
            
            TextInput::make('name')
                ->label(__('Name'))
                ->required()
                ->maxLength(200),
            
            Select::make('company_id')
                ->label(__('Company'))
                ->relationship('company', 'name')
                ->default(function () {
                    $selectedCompanyId = session('selected_company_id');
                    if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                        return $selectedCompanyId;
                    }
                    return null; // Allow null for global records
                })
                ->hidden(true) // Always hidden - company is controlled by global selector
                ->required(function () {
                    $selectedCompanyId = session('selected_company_id');
                    return !($selectedCompanyId && $selectedCompanyId === 'all');
                })
                ->searchable()
                ->preload()
                ->nullable(),
            
            Select::make('cash_flow')
                ->label(__('Cash Flow'))
                ->options([
                    'operating' => __('Operating Activities'),
                    'investing' => __('Investing Activities'),
                    'financing' => __('Financing Activities'),
                    'undefined' => __('Undefined'),
                ])
                ->default('undefined')
                ->nullable()
                ->hidden(function (callable $get) {
                    // Check if current form data indicates a top-level account
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);
                        return $account && $account->isClassificationRoot();
                    }
                    return false;
                }),
            
            Select::make('account_type')
                ->label(__('Rasio Type'))
                ->required()
                ->options([
                    'current_asset' => __('Current Assets'),
                    'fixed_asset' => __('Fixed Assets'),
                    'other_asset' => __('Other Assets'),
                    'current_liability' => __('Current Liabilities'),
                    'long_term_liability' => __('Long Term Liabilities'),
                    'equity' => __('Equity'),
                    'revenue' => __('Revenue'),
                    'expense' => __('Expense'),
                    'cost_of_goods_sold' => __('Cost of Goods Sold'),
                    'other_income' => __('Other Income'),
                    'other_expense' => __('Other Expense'),
                    'other_income_expense' => __('Other Income/Expense'),
                ])
                ->searchable()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state === Account::OTHER_INCOME_EXPENSE) {
                        $set('is_header', true);
                        $set('classification_type', 'expense');
                    }
                })
                ->hidden(function (callable $get) {
                    // Check if current form data indicates a top-level account
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);
                        return $account && $account->isClassificationRoot();
                    }
                    return false;
                }),

            Checkbox::make('is_header')
                ->label(__('Header Account'))
                ->helperText(__('Header accounts group child accounts and are not used for journal postings.'))
                ->default(false),
            
            Checkbox::make('is_cash_bank')
                ->label(__('Is Cash/Bank Account'))
                ->default(false)
                ->hidden(function (callable $get) {
                    // Check if current form data indicates a top-level account
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);
                        return $account && $account->isClassificationRoot();
                    }
                    return false;
                }),
            
            Checkbox::make('is_active')
                ->label(__('Active'))
                ->default(true)
                ->hidden(function (callable $get) {
                    // Check if current form data indicates a top-level account
                    $accountId = $get('id');
                    if ($accountId) {
                        $account = Account::find($accountId);
                        return $account && $account->isClassificationRoot();
                    }
                    return false;
                }),
        ];
    }
}
