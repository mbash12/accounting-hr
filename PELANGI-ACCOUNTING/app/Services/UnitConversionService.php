<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Collection;

class UnitConversionService
{
    /**
     * Convert a quantity from one unit to another using global UOM categories.
     * Both units must belong to the same category.
     */
    public function convert(float $qty, int $fromUnitId, int $toUnitId, ?int $companyId = null): float
    {
        if ($fromUnitId === $toUnitId) {
            return $qty;
        }

        $factor = $this->getConversionFactor($fromUnitId, $toUnitId, $companyId);
        return $qty * $factor;
    }

    /**
     * Get the global conversion factor between two units using UOM categories.
     * Returns: 1 unit of $fromUnitId = X units of $toUnitId.
     *
     * Falls back to product-specific conversion if units are not in the same category
     * and a $product is provided.
     */
    public function getConversionFactor(int $fromUnitId, int $toUnitId, ?int $companyId = null, ?Product $product = null): float
    {
        if ($fromUnitId === $toUnitId) {
            return 1.0;
        }

        $fromUnit = Unit::find($fromUnitId);
        $toUnit = Unit::find($toUnitId);

        // Global category-based conversion
        if ($fromUnit && $toUnit && $fromUnit->unit_category_id && $toUnit->unit_category_id
            && $fromUnit->unit_category_id === $toUnit->unit_category_id
        ) {
            // 1 fromUnit → fromUnit.conversion_factor base units
            // base units / toUnit.conversion_factor → toUnit
            $fromFactor = (float) $fromUnit->conversion_factor;
            $toFactor = (float) $toUnit->conversion_factor;

            if ($toFactor == 0) {
                return 1.0;
            }

            return $fromFactor / $toFactor;
        }

        // Fallback: product-specific conversion via product_units
        if ($product) {
            return $this->getProductConversionFactor($product, $fromUnitId, $toUnitId);
        }

        return 1.0;
    }

    /**
     * Get the conversion factor for a product's alternate unit relative to its base unit.
     * Kept for backward compatibility with form callbacks.
     *
     * Returns: 1 unit of $unitId = X units of the product's base unit.
     */
    public function getProductConversionFactor(Product $product, int $fromUnitId, int $toUnitId): float
    {
        if ($fromUnitId === $toUnitId) {
            return 1.0;
        }

        // Try product_units: factor for $fromUnitId relative to product base
        if ($toUnitId === $product->unit_id) {
            // Converting from alternate to base: look up product_units
            if ($fromUnitId === $product->unit_id) {
                return 1.0;
            }

            $productUnit = ProductUnit::where('product_id', $product->id)
                ->where('unit_id', $fromUnitId)
                ->first();

            return $productUnit ? (float) $productUnit->conversion_factor : 1.0;
        }

        if ($fromUnitId === $product->unit_id) {
            // Converting from base to alternate: invert product_units factor
            $productUnit = ProductUnit::where('product_id', $product->id)
                ->where('unit_id', $toUnitId)
                ->first();

            if ($productUnit && $productUnit->conversion_factor != 0) {
                return 1.0 / (float) $productUnit->conversion_factor;
            }

            return 1.0;
        }

        // Both are alternate units: pivot through base
        $fromFactor = 1.0;
        $toFactor = 1.0;

        if ($fromUnitId !== $product->unit_id) {
            $fromPu = ProductUnit::where('product_id', $product->id)
                ->where('unit_id', $fromUnitId)
                ->first();
            $fromFactor = $fromPu ? (float) $fromPu->conversion_factor : 1.0;
        }

        if ($toUnitId !== $product->unit_id) {
            $toPu = ProductUnit::where('product_id', $product->id)
                ->where('unit_id', $toUnitId)
                ->first();
            $toFactor = $toPu ? (float) $toPu->conversion_factor : 1.0;
        }

        if ($toFactor == 0) {
            return 1.0;
        }

        return $fromFactor / $toFactor;
    }

    /**
     * Get all available units for a product (base unit + alternate units).
     * Conversion factors come from global categories when available,
     * falling back to product_units.
     */
    public function getAvailableUnits(Product $product): Collection
    {
        $units = collect();
        $seenUnitIds = [];

        // 1. Base unit
        if ($product->unit) {
            $seenUnitIds[] = $product->unit_id;
            $units->push([
                'unit_id' => $product->unit_id,
                'unit_name' => $product->unit->name,
                'conversion_factor' => 1.0,
                'is_purchase_unit' => true,
                'is_sales_unit' => true,
                'is_base' => true,
            ]);
        }

        // 2. Explicit product_units (purchase/sales flags take priority)
        $product->productUnits()->with('unit')->each(function ($pu) use ($units, $product, &$seenUnitIds) {
            $seenUnitIds[] = $pu->unit_id;
            $factor = $this->getConversionFactor($pu->unit_id, $product->unit_id, null, $product);
            $units->push([
                'unit_id' => $pu->unit_id,
                'unit_name' => $pu->unit->name,
                'conversion_factor' => $factor,
                'is_purchase_unit' => $pu->is_purchase_unit,
                'is_sales_unit' => $pu->is_sales_unit,
                'is_base' => false,
            ]);
        });

        // 3. Category siblings: all other units in the same UOM category
        if ($product->unit && $product->unit->unit_category_id) {
            $categoryUnits = Unit::where('unit_category_id', $product->unit->unit_category_id)
                ->whereNotIn('id', $seenUnitIds)
                ->get();

            foreach ($categoryUnits as $catUnit) {
                $factor = $this->getConversionFactor($catUnit->id, $product->unit_id, null, $product);
                $units->push([
                    'unit_id' => $catUnit->id,
                    'unit_name' => $catUnit->name,
                    'conversion_factor' => $factor,
                    'is_purchase_unit' => true,
                    'is_sales_unit' => true,
                    'is_base' => false,
                ]);
            }
        }

        return $units;
    }

    /**
     * Get units available for purchasing a product.
     */
    public function getPurchaseUnits(Product $product): Collection
    {
        return $this->getAvailableUnits($product)->filter(fn ($u) => $u['is_purchase_unit']);
    }

    /**
     * Get units available for selling a product.
     */
    public function getSalesUnits(Product $product): Collection
    {
        return $this->getAvailableUnits($product)->filter(fn ($u) => $u['is_sales_unit']);
    }

    /**
     * Get unit options as [unit_id => label] for form dropdowns.
     * Label includes conversion info for alternate units.
     */
    public function getUnitOptions(Product $product, string $context = 'all'): array
    {
        $units = match ($context) {
            'purchase' => $this->getPurchaseUnits($product),
            'sales' => $this->getSalesUnits($product),
            default => $this->getAvailableUnits($product),
        };

        $options = [];
        foreach ($units as $u) {
            $label = $u['unit_name'];
            if (!$u['is_base'] && $u['conversion_factor'] != 1) {
                $baseName = $product->unit?->name ?? 'base unit';
                $label .= ' (1 ' . $u['unit_name'] . ' = ' . rtrim(rtrim(number_format($u['conversion_factor'], 6), '0'), '.') . ' ' . $baseName . ')';
            }
            $options[$u['unit_id']] = $label;
        }

        return $options;
    }
}
