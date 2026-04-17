<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class NumberInput extends TextInput
{
    protected bool $isDecimal = false;

    /**
     * Parse a value (string or numeric) to a clean float.
     * Handles Indonesian format: dots are thousands, comma is decimal.
     *
     * When $integerOnly is true, both '.' and ',' are ALWAYS treated as
     * thousands separators (never as decimal), which removes the ambiguity
     * for integer-only fields (e.g., product prices with decimal(false)).
     * When null, the method auto-detects based on the heuristic (backward compatible).
     */
    public static function parseToFloat($value, ?bool $integerOnly = null): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_float($value)) {
            return $value;
        }

        $v = trim((string) $value);

        if ($v === '') {
            return 0.0;
        }

        $v = preg_replace('/[^0-9,\.\-]/', '', $v);
        if ($v === '' || $v === '-' || $v === '.' || $v === ',') {
            return 0.0;
        }

        $isNegative = str_contains($v, '-');
        $v = str_replace('-', '', $v);

        if ($integerOnly === true) {
            // Integer mode: strip all separators unconditionally.
            $v = str_replace([',', '.'], '', $v);
        } else {
            $lastComma = strrpos($v, ',');
            $lastDot = strrpos($v, '.');

            if ($lastComma !== false && $lastDot !== false) {
                if ($lastComma > $lastDot) {
                    $v = str_replace('.', '', $v);
                    $v = str_replace(',', '.', $v);
                } else {
                    $v = str_replace(',', '', $v);
                }
            } elseif ($lastComma !== false) {
                $digitsAfter = strlen($v) - $lastComma - 1;
                if ($digitsAfter > 0 && $digitsAfter <= 2) {
                    $v = str_replace('.', '', $v);
                    $v = str_replace(',', '.', $v);
                } else {
                    $v = str_replace(',', '', $v);
                    $v = str_replace('.', '', $v);
                }
            } elseif ($lastDot !== false) {
                $digitsAfter = strlen($v) - $lastDot - 1;
                $dotCount = substr_count($v, '.');
                if ($dotCount > 1) {
                    $v = str_replace('.', '', $v);
                } elseif ($digitsAfter > 0 && $digitsAfter <= 2) {
                    $v = str_replace(',', '', $v);
                } else {
                    $v = str_replace('.', '', $v);
                    $v = str_replace(',', '', $v);
                }
            } else {
                $v = str_replace([',', '.'], '', $v);
            }
        }

        $v = preg_replace('/[^0-9.]/', '', $v);

        $parts = explode('.', $v);
        if (count($parts) > 2) {
            $v = $parts[0] . '.' . implode('', array_slice($parts, 1));
        }

        if ($isNegative && $v !== '' && $v !== '0') {
            $v = '-' . $v;
        }

        return is_numeric($v) ? (float) $v : 0.0;
    }

    public static function parseToDecimalString($value, int $scale = 2): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value . '.' . str_repeat('0', $scale);
        }

        if (is_float($value)) {
            $formatted = number_format($value, $scale, '.', '');
            return $formatted === '' ? null : $formatted;
        }

        $v = trim((string) $value);
        if ($v === '') {
            return null;
        }

        $v = preg_replace('/[^0-9,\.\-]/', '', $v);
        if ($v === '' || $v === '-' || $v === '.' || $v === ',') {
            return null;
        }

        $isNegative = str_contains($v, '-');
        $v = str_replace('-', '', $v);

        $lastComma = strrpos($v, ',');
        $lastDot = strrpos($v, '.');

        $decimalSeparator = null;
        if ($lastComma !== false && $lastDot !== false) {
            $decimalSeparator = $lastComma > $lastDot ? ',' : '.';
        } elseif ($lastComma !== false) {
            $digitsAfter = strlen($v) - $lastComma - 1;
            $decimalSeparator = ($digitsAfter > 0 && $digitsAfter <= $scale) ? ',' : null;
        } elseif ($lastDot !== false) {
            $digitsAfter = strlen($v) - $lastDot - 1;
            $dotCount = substr_count($v, '.');
            if ($dotCount === 1 && $digitsAfter > 0 && $digitsAfter <= $scale) {
                $decimalSeparator = '.';
            } else {
                $decimalSeparator = null;
            }
        }

        if ($decimalSeparator === ',') {
            $v = str_replace('.', '', $v);
            $v = str_replace(',', '.', $v);
        } elseif ($decimalSeparator === '.') {
            $v = str_replace(',', '', $v);
        } else {
            $v = str_replace([',', '.'], '', $v);
        }

        $v = preg_replace('/[^0-9.]/', '', $v);
        if ($v === '' || $v === '.') {
            return null;
        }

        $parts = explode('.', $v, 3);
        $integerPart = $parts[0] ?? '0';
        $fractionPart = $parts[1] ?? '';

        $integerPart = ltrim($integerPart, '0');
        if ($integerPart === '') {
            $integerPart = '0';
        }

        if ($fractionPart !== '' && strlen($fractionPart) > $scale) {
            return null;
        }

        $fractionPart = str_pad($fractionPart, $scale, '0');

        $result = $integerPart . '.' . $fractionPart;
        if ($isNegative && $result !== ('0.' . str_repeat('0', $scale))) {
            $result = '-' . $result;
        }

        return $result;
    }

    public static function formatIntegerWithDots(int $value): string
    {
        return number_format($value, 0, ',', '.');
    }

    public static function roundDecimalStringHalfUpToInt(string $decimal): int
    {
        $decimal = trim($decimal);
        if ($decimal === '') {
            return 0;
        }

        $isNegative = str_starts_with($decimal, '-');
        if ($isNegative) {
            $decimal = substr($decimal, 1);
        }

        $parts = explode('.', $decimal, 3);
        $integerPart = $parts[0] ?? '0';
        $fractionPart = $parts[1] ?? '0';
        $fractionPart = str_pad(preg_replace('/\D/', '', $fractionPart), 2, '0');
        $fractionPart = substr($fractionPart, 0, 2);

        $int = (int) preg_replace('/\D/', '', $integerPart);
        if ((int) $fractionPart >= 50) {
            $int += 1;
        }

        return $isNegative ? -$int : $int;
    }

    public static function formatRoundedIntegerDisplay($value, int $scale = 2): string
    {
        $decimal = static::parseToDecimalString($value, $scale);
        if ($decimal === null) {
            return '0';
        }

        return static::formatIntegerWithDots(static::roundDecimalStringHalfUpToInt($decimal));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->decimal(false);

        // Clean state when saving to database
        $this->dehydrateStateUsing(function ($state) {
            if ($state === null || $state === '') {
                return null;
            }

            return static::parseToFloat($state, ! $this->isDecimal);
        });
    }

    public function decimal(bool $decimal = true): static
    {
        $this->isDecimal = $decimal;

        // Apply live Indonesian money formatting while user types.
        // Use single quotes for JS string literals to avoid clashing with the
        // double-quoted HTML attribute that Blade emits for x-mask:dynamic.
        $this->mask(
            RawJs::make($decimal ? "\$money(\$input, ',', '.')" : "\$money(\$input, ',', '.', 0)")
        );

        $this->formatStateUsing(function ($state) use ($decimal) {
            if ($state === null || $state === '') {
                return null;
            }

            // Parse formatted strings safely (e.g. "1.234,56") before re-formatting.
            // Do NOT force integerOnly here because state coming from the model
            // (Eloquent decimal:2 cast) is a raw decimal string like "10000.00"
            // that must be parsed with '.' as the decimal separator.
            $normalizedValue = static::parseToFloat($state);

            return number_format($normalizedValue, $decimal ? 2 : 0, ',', '.');
        });

        return $this;
    }

    public function isDecimal(): bool
    {
        return $this->isDecimal;
    }
}
