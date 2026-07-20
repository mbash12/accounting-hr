<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Services\DataCleanupService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use RuntimeException;
use UnitEnum;

class ManageDataCleanup extends Page implements HasTable
{
    use HasPageShield;
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Data Cleanup';

    protected static string|UnitEnum|null $navigationGroup = 'Supporting Data';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'Data Cleanup';

    protected string $view = 'filament.pages.manage-data-cleanup';

    public static function getNavigationLabel(): string
    {
        return __('Data Cleanup');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Supporting Data');
    }

    public function getTitle(): string
    {
        return __('Data Cleanup');
    }

    public function hasCompanySelected(): bool
    {
        $companyId = session('selected_company_id');

        return $companyId && $companyId !== 'all';
    }

    public function companyId(): ?int
    {
        if (!$this->hasCompanySelected()) {
            return null;
        }

        return (int) session('selected_company_id');
    }

    public function companyName(): ?string
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return null;
        }

        return Company::query()->whereKey($companyId)->value('name');
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(function (?string $search, array $filters): array {
                if (!$this->hasCompanySelected()) {
                    return [];
                }

                $service = app(DataCleanupService::class);
                $datasets = $service->datasets();
                $counts = $service->counts($this->companyId());
                $groupFilter = $filters['group']['value'] ?? null;

                $records = [];
                foreach ($datasets as $key => $meta) {
                    if (filled($groupFilter) && $meta['group'] !== $groupFilter) {
                        continue;
                    }

                    if (filled($search)) {
                        $haystack = strtolower($meta['label'] . ' ' . $meta['group'] . ' ' . $meta['description']);
                        if (!str_contains($haystack, strtolower($search))) {
                            continue;
                        }
                    }

                    $records[$key] = [
                        'id' => $key,
                        'label' => $meta['label'],
                        'group' => $meta['group'],
                        'description' => $meta['description'],
                        'danger' => $meta['danger'] ? __('High') : __('Normal'),
                        'count' => $counts[$key] ?? 0,
                    ];
                }

                return $records;
            })
            ->resolveSelectedRecordsUsing(function (array $keys): Collection {
                if (!$this->hasCompanySelected()) {
                    return collect();
                }

                $service = app(DataCleanupService::class);
                $datasets = $service->datasets();
                $counts = $service->counts($this->companyId());
                $out = [];

                foreach ($keys as $key) {
                    if (!isset($datasets[$key])) {
                        continue;
                    }
                    $meta = $datasets[$key];
                    $out[$key] = [
                        'id' => $key,
                        'label' => $meta['label'],
                        'group' => $meta['group'],
                        'description' => $meta['description'],
                        'danger' => $meta['danger'] ? __('High') : __('Normal'),
                        'count' => $counts[$key] ?? 0,
                    ];
                }

                return collect($out);
            })
            ->columns([
                TextColumn::make('label')
                    ->label(__('Dataset'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->sortable(),
                TextColumn::make('count')
                    ->label(__('Records'))
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('danger')
                    ->label(__('Danger'))
                    ->badge()
                    ->color(fn (string $state): string => $state === __('High') ? 'danger' : 'gray'),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->wrap()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label(__('Group'))
                    ->options([
                        'Master Data' => __('Master Data'),
                        'Sales' => __('Sales'),
                        'Purchasing' => __('Purchasing'),
                        'Cash & Bank' => __('Cash & Bank'),
                        'General Ledger' => __('General Ledger'),
                        'HR & Payroll' => __('HR & Payroll'),
                    ]),
            ])
            ->paginated([25, 50, 100])
            ->searchable()
            ->toolbarActions([
                BulkActionGroup::make([
                    $this->clearSelectedBulkAction(),
                ]),
            ])
            ->emptyStateHeading(
                $this->hasCompanySelected()
                    ? __('No datasets')
                    : __('Select a company')
            )
            ->emptyStateDescription(
                $this->hasCompanySelected()
                    ? null
                    : __('Please select a specific company from the global selector.')
            );
    }

    protected function clearSelectedBulkAction(): BulkAction
    {
        return BulkAction::make('clearSelected')
            ->label(__('Clear selected'))
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->deselectRecordsAfterCompletion()
            ->modalHeading(__('Clear selected datasets'))
            ->modalDescription(fn () => __('Clearing data for: :company', [
                'company' => $this->companyName() ?? '—',
            ]))
            ->form(function (Collection $records): array {
                $keys = $records->keys()->map(fn ($k) => (string) $k)->values()->all();
                $companyId = $this->companyId();
                $companyName = $this->companyName() ?? '';

                return [
                    Radio::make('mode')
                        ->label(__('Side effect'))
                        ->options([
                            DataCleanupService::MODE_CASCADE => __('Cascade delete — also delete nested / related data'),
                            DataCleanupService::MODE_NULLIFY => __('Nullify FK — set nullable references to null; block if required'),
                        ])
                        ->default(DataCleanupService::MODE_CASCADE)
                        ->required()
                        ->live(),
                    Placeholder::make('preview')
                        ->label(__('Preview'))
                        ->content(function (Get $get) use ($keys, $companyId): HtmlString {
                            if (!$companyId || $keys === []) {
                                return new HtmlString('<p class="text-sm text-gray-500">' . e(__('Nothing selected.')) . '</p>');
                            }

                            $mode = $get('mode') ?: DataCleanupService::MODE_CASCADE;
                            $preview = app(DataCleanupService::class)->preview($keys, $companyId, $mode);

                            $html = '';
                            if (!$preview['ok']) {
                                $html .= '<div class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800 mb-3"><ul class="list-disc pl-4">';
                                foreach ($preview['errors'] as $error) {
                                    $html .= '<li>' . e($error) . '</li>';
                                }
                                $html .= '</ul></div>';
                            }

                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left text-gray-500"><th class="py-1 pr-3">' . e(__('Dataset')) . '</th><th class="py-1 pr-3">' . e(__('Records')) . '</th><th class="py-1">' . e(__('Related impact')) . '</th></tr></thead><tbody>';
                            foreach ($preview['rows'] as $row) {
                                $related = collect($row['related'])
                                    ->map(fn ($r) => e($r['label']) . ' (' . e((string) $r['count']) . ', ' . e($r['action']) . ')')
                                    ->implode('; ');
                                $html .= '<tr class="border-t border-gray-100"><td class="py-1.5 pr-3 font-medium">' . e($row['label']) . '</td><td class="py-1.5 pr-3">' . e((string) $row['count']) . '</td><td class="py-1.5 text-gray-600">' . ($related ?: '—') . '</td></tr>';
                            }
                            $html .= '</tbody></table></div>';

                            return new HtmlString($html);
                        }),
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
            ->action(function (Collection $records, array $data): void {
                $companyId = $this->companyId();
                if (!$companyId) {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body(__('Please select a specific company first.'))
                        ->send();

                    return;
                }

                $keys = $records->keys()->map(fn ($k) => (string) $k)->values()->all();
                $mode = $data['mode'] ?? DataCleanupService::MODE_CASCADE;

                try {
                    $result = app(DataCleanupService::class)->clear($keys, $companyId, $mode);
                    $totalDeleted = array_sum($result['deleted']);
                    $totalNullified = array_sum($result['nullified']);

                    Notification::make()
                        ->success()
                        ->title(__('Data cleared'))
                        ->body(__('Deleted :deleted record(s). Nullified :nullified reference(s).', [
                            'deleted' => $totalDeleted,
                            'nullified' => $totalNullified,
                        ]))
                        ->send();
                } catch (RuntimeException $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Cannot clear data'))
                        ->body($e->getMessage())
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('Error'))
                        ->body($e->getMessage())
                        ->send();
                }
            })
            ->disabled(fn (): bool => !$this->hasCompanySelected());
    }
}
