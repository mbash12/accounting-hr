<?php

namespace App\Services;

use App\Filament\Forms\Components\NumberInput;
use App\Models\AccountMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdditionalChargesHelper
{
    public static function mappingDocumentType(string $side): string
    {
        return $side === 'purchase' ? 'purchase_invoice' : 'sales_invoice';
    }

    public static function defaultAccountId(?int $companyId, string $side = 'sales'): ?int
    {
        $account = AccountMapping::getAccountMapping(
            self::mappingDocumentType($side),
            'other_charges',
            $companyId,
        );

        return $account?->id;
    }

    public static function sumFromRows(array $rows): float
    {
        $total = 0.0;

        foreach ($rows as $row) {
            $total += NumberInput::parseToFloat($row['amount'] ?? 0);
        }

        return round($total, 2);
    }

    /**
     * Prefer relationship rows when present (including empty); fall back to scalar only when unset.
     */
    public static function resolveAmount(mixed $chargeRows, mixed $scalarFallback = 0): float
    {
        if (is_array($chargeRows)) {
            return self::sumFromRows($chargeRows);
        }

        return round(NumberInput::parseToFloat($scalarFallback ?? 0), 2);
    }

    public static function syncTotal(Model $document): float
    {
        if (! method_exists($document, 'otherCharges')) {
            return (float) ($document->other_charges ?? 0);
        }

        $total = round((float) $document->otherCharges()->sum('amount'), 2);
        $document->other_charges = $total;

        return $total;
    }

    /**
     * Infer sales vs purchase side from the source document class/table.
     */
    public static function sideForDocument(Model $source): string
    {
        $class = class_basename($source);

        return str_starts_with($class, 'Purchase') ? 'purchase' : 'sales';
    }

    /**
     * @return Collection<int, array{name: string, account_id: mixed, amount: float, sort_order: int}>
     */
    public static function rowsForCopy(Model $source, float $ratio = 1.0): Collection
    {
        $companyId = isset($source->company_id) ? (int) $source->company_id : null;
        $side = self::sideForDocument($source);
        $fallbackAccountId = self::defaultAccountId($companyId, $side);

        if (method_exists($source, 'otherCharges')) {
            $source->loadMissing('otherCharges');

            if ($source->otherCharges->isNotEmpty()) {
                return $source->otherCharges
                    ->values()
                    ->map(function ($row, int $index) use ($ratio, $fallbackAccountId) {
                        return [
                            'name' => $row->name,
                            'account_id' => $row->account_id ?: $fallbackAccountId,
                            'amount' => round((float) $row->amount * $ratio, 2),
                            'sort_order' => $row->sort_order ?? $index,
                        ];
                    })
                    ->filter(fn (array $row) => ($row['amount'] ?? 0) > 0)
                    ->values();
            }
        }

        $amount = round((float) ($source->other_charges ?? 0) * $ratio, 2);
        if ($amount <= 0) {
            return collect();
        }

        return collect([[
            'name' => 'Other Charges',
            'account_id' => $source->other_charges_account_id ?: $fallbackAccountId,
            'amount' => $amount,
            'sort_order' => 0,
        ]]);
    }

    public static function createRows(Model $target, iterable $rows): void
    {
        if (! method_exists($target, 'otherCharges')) {
            return;
        }

        $rows = collect($rows)->values();
        if ($rows->isEmpty()) {
            return;
        }

        foreach ($rows as $index => $row) {
            $target->otherCharges()->create([
                'name' => $row['name'] ?? 'Other Charges',
                'account_id' => $row['account_id'] ?? null,
                'amount' => $row['amount'] ?? 0,
                'sort_order' => $row['sort_order'] ?? $index,
            ]);
        }

        self::syncTotal($target);
        $target->saveQuietly();
    }
}
