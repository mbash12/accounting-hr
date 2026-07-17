<?php

namespace App\Filament\Forms\Components;

use App\Models\Account;
use App\Services\AdditionalChargesHelper;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;

class AdditionalChargesSection
{
    /**
     * Other Charges in the original totals slot: sum row toggles charge rows.
     *
     * @param  Closure(callable $set, callable $get): void  $recalculate
     */
    public static function make(string $side, Closure $recalculate, bool $showAccount = true): Group
    {
        $sync = function (callable $set, callable $get) use ($recalculate): void {
            $rows = $get('otherCharges') ?? [];
            $sum = AdditionalChargesHelper::sumFromRows(is_array($rows) ? $rows : []);
            $set('other_charges', $sum);
            $recalculate($set, $get);
        };

        $amountFields = RoundedIntegerMoneyInput::schema(
            name: 'amount',
            label: __('Amount'),
            required: true,
            defaultDecimal: '0.00',
            afterUpdated: function ($decimal, callable $set, callable $get) use ($sync): void {
                $parentSet = function (string $key, $value) use ($set): void {
                    $set('../../' . $key, $value);
                };
                $parentGet = function (string $key) use ($get) {
                    return $get('../../' . $key);
                };
                $sync($parentSet, $parentGet);
            },
        );

        foreach ($amountFields as $component) {
            if ($component instanceof TextInput) {
                $component
                    ->hiddenLabel()
                    ->placeholder('0')
                    ->columnSpan($showAccount ? 3 : 5);
            }
        }

        $rowSchema = [
            TextInput::make('name')
                ->hiddenLabel()
                ->placeholder(__('e.g. Ongkir'))
                ->required()
                ->maxLength(255)
                ->dehydrated(true)
                ->columnSpan($showAccount ? 4 : 7),
        ];

        if ($showAccount) {
            $rowSchema[] = Select::make('account_id')
                ->hiddenLabel()
                ->placeholder(__('COA'))
                ->required()
                ->searchable()
                ->preload()
                ->dehydrated(true)
                ->columnSpan(5)
                ->default(function (callable $get) use ($side) {
                    return self::resolveDefaultAccountId($get, $side);
                })
                ->afterStateHydrated(function (Select $component, $state, callable $get) use ($side): void {
                    if (filled($state)) {
                        return;
                    }

                    $defaultId = self::resolveDefaultAccountId($get, $side);
                    if ($defaultId) {
                        $component->state($defaultId);
                    }
                })
                ->options(fn () => self::accountOptions())
                ->getSearchResultsUsing(fn (string $search) => self::accountOptions($search))
                ->getOptionLabelUsing(function ($value) {
                    $account = Account::find($value);

                    return $account ? "{$account->code} - {$account->name}" : $value;
                });
        } else {
            // Keep column present for relationship hydration, but never shown/required on orders.
            $rowSchema[] = Hidden::make('account_id')
                ->dehydrated(true)
                ->default(null);
        }

        $rowSchema = [...$rowSchema, ...$amountFields];

        return Group::make([
            Placeholder::make('empty_col_other_charges_sum')
                ->hiddenLabel()
                ->columnSpan(1),
            Placeholder::make('other_charges_total_display')
                ->inlineLabel()
                ->label(new HtmlString(
                    '<span class="fi-fo-other-charges-label-text">' . e(__('Other Charges')) . '</span>'
                    . '<button type="button" class="fi-link fi-size-sm fi-fo-other-charges-add" data-other-charges-add="1">'
                    . '<svg class="fi-icon fi-size-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">'
                    . '<path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />'
                    . '</svg>'
                    . '<span>' . e(__('Add')) . '</span>'
                    . '</button>'
                ))
                ->content(function (callable $get) {
                    $amount = AdditionalChargesHelper::resolveAmount(
                        $get('otherCharges') ?? null,
                        $get('other_charges') ?? 0,
                    );
                    $formatted = 'Rp ' . number_format($amount, 0, ',', '.');

                    return new HtmlString(
                        '<div class="fi-fo-other-charges-row">'
                        . '<span class="fi-fo-other-charges-toggle-value">' . e($formatted) . '</span>'
                        . '<svg class="fi-fo-other-charges-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">'
                        . '<path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />'
                        . '</svg>'
                        . '</div>'
                    );
                })
                ->extraAttributes([
                    'class' => 'fi-fo-other-charges-toggle',
                    'role' => 'button',
                    'tabindex' => '0',
                    'aria-expanded' => 'false',
                ])
                ->columnSpan(1),

            Placeholder::make('empty_col_other_charges_rows')
                ->hiddenLabel()
                ->columnSpan(1),
            Repeater::make('otherCharges')
                ->relationship()
                ->hiddenLabel()
                ->addActionLabel(__('Add'))
                ->addActionAlignment(Alignment::Start)
                ->addAction(fn ($action) => $action
                    ->link()
                    ->icon('heroicon-m-plus')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes(['data-other-charges-native-add' => '1']))
                ->defaultItems(0)
                ->reorderable(false)
                ->compact()
                ->cloneable(false)
                ->collapsible(false)
                ->columnSpan(1)
                ->extraFieldWrapperAttributes([
                    'class' => 'fi-fo-other-charges-repeater',
                ])
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) use ($sync): void {
                    $sync($set, $get);
                })
                ->deleteAction(fn ($action) => $action
                    ->iconButton()
                    ->icon('heroicon-m-x-mark')
                    ->color('gray')
                    ->size('sm')
                    ->after(function (callable $set, callable $get) use ($sync): void {
                        $sync($set, $get);
                    }))
                ->schema($rowSchema)
                ->columns(12),

            Hidden::make('other_charges')
                ->default(0)
                ->dehydrated(true),
        ])
            ->columns(2)
            ->columnSpanFull()
            ->extraAttributes([
                'class' => 'fi-fo-other-charges-block is-collapsed',
                'data-other-charges-block' => '1',
            ]);
    }

    protected static function resolveDefaultAccountId(callable $get, string $side): ?int
    {
        $companyId = $get('../../company_id') ?? $get('company_id');
        if (! $companyId) {
            $sessionCompany = session('selected_company_id');
            $companyId = ($sessionCompany && $sessionCompany !== 'all') ? $sessionCompany : null;
        }

        return AdditionalChargesHelper::defaultAccountId(
            $companyId ? (int) $companyId : null,
            $side,
        );
    }

    protected static function accountOptions(?string $search = null): array
    {
        $companyId = session('selected_company_id');

        $query = Account::query()
            ->where('is_header', false)
            ->where('is_active', true);

        if ($companyId && $companyId !== 'all') {
            $query->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)
                    ->orWhereNull('company_id');
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(code) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $results = [];
        foreach ($query->orderBy('code')->limit($search ? 50 : 100)->get() as $account) {
            $results[$account->id] = "{$account->code} - {$account->name}";
        }

        return $results;
    }
}
