<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Validation\ValidationRule;

class RoundedIntegerMoneyInput
{
    public static function schema(
        string $name,
        ?string $label = null,
        bool $required = false,
        bool $inlineLabel = false,
        int $scale = 2,
        ?Closure $afterUpdated = null,
        array $extraInputAttributes = [],
        bool $liveOnBlur = true,
        ?string $defaultDecimal = '0.00',
        int | array | null $columnSpan = null,
        array $rules = [],
    ): array {
        $displayName = "{$name}_display";
        $formattingFlagName = "{$name}_is_formatting";

        $display = TextInput::make($displayName)
            ->label($label)
            ->dehydrated(false)
            ->inputMode('decimal')
            ->extraInputAttributes(array_merge(['style' => 'text-align:right'], $extraInputAttributes))
            ->afterStateHydrated(function (callable $set, callable $get) use ($name, $displayName, $scale) {
                $set($displayName, NumberInput::formatRoundedIntegerDisplay($get($name), $scale));
            })
            ->afterStateUpdated(function ($state, callable $set, callable $get) use ($name, $displayName, $formattingFlagName, $scale, $afterUpdated) {
                if ($get($formattingFlagName)) {
                    return;
                }

                $decimal = NumberInput::parseToDecimalString($state, $scale);
                if ($decimal === null) {
                    $set($name, null);
                    return;
                }

                $set($name, $decimal);

                $formatted = NumberInput::formatRoundedIntegerDisplay($decimal, $scale);
                if ($formatted !== (string) $state) {
                    $set($formattingFlagName, true);
                    $set($displayName, $formatted);
                    $set($formattingFlagName, false);
                }

                if ($afterUpdated) {
                    $afterUpdated($decimal, $set, $get);
                }
            })
            ->rules(array_merge([
                new class ($scale) implements ValidationRule
                {
                    public function __construct(private readonly int $scale)
                    {
                    }

                    public function validate(string $attribute, mixed $value, Closure $fail): void
                    {
                        if ($value === null || $value === '') {
                            return;
                        }

                        if (NumberInput::parseToDecimalString($value, $this->scale) === null) {
                            $fail(__('Invalid number format.'));
                        }
                    }
                },
            ], $rules));

        if ($required) {
            $display->required();
        }

        if ($inlineLabel) {
            $display->inlineLabel();
        }

        if ($liveOnBlur) {
            $display->live(onBlur: true);
        }

        if ($columnSpan !== null) {
            $display->columnSpan($columnSpan);
        }

        $hidden = Hidden::make($name)
            ->default($defaultDecimal)
            ->live()
            ->afterStateUpdated(function ($state, callable $set) use ($displayName, $scale) {
                $formatted = NumberInput::formatRoundedIntegerDisplay($state, $scale);
                $set($displayName, $formatted);
            })
            ->dehydrateStateUsing(fn ($state) => NumberInput::parseToDecimalString($state, $scale));

        $flag = Hidden::make($formattingFlagName)
            ->default(false)
            ->dehydrated(false);

        return [$hidden, $flag, $display];
    }
}
