<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Collection;

class UnitConversionService
{
    /**
     * Convert a quantity from one unit to another for a given product.
     * All conversions pivot through the product's base unit.
     */
    public function convert(Product $product, float $qty, int $fromUnitId, int $toUnitId): float
    {
        if ($fromUnitId === $toUnitId) {
            return $qty;
        }

        $baseQty = $this->convertToBaseUnit($product, $qty, $fromUnitId);
        return $this->convertFromBaseUnit($product, $baseQty, $toUnitId);
    }

    /**
     * Convert a quantity from an alternate unit to the product's base unit.
     */
    public function convertToBaseUnit(Product $product, float $qty, int $fromUnitId): float
    {
        if ($fromUnitId === $product->unit_id) {
            return $qty;
        }

        $factor = $this->getConversionFactor($product, $fromUnitId);
        return $qty * $factor;
    }

    /**
     * Convert a quantity from the product's base unit to an alternate unit.
     */
    public function convertFromBaseUnit(Product $product, float $qty, int $toUnitId): float
    {
        if ($toUnitId === $product->unit_id) {
            return $qty;
        }

        $factor = $this->getConversionFactor($product, $toUnitId);
        if ($factor == 0) {
            return $qty;
        }

        return $qty / $factor;
    }

    /**
     * Get the conversion factor for a unit relative to the product's base unit.
     * Returns how many base units = 1 of the given unit.
     */
    public function getConversionFactor(Product $product, int $unitId): float
    {
        if ($unitId === $product->unit_id) {
            return 1.0;
        }

        $productUnit = ProductUnit::where('product_id', $product->id)
            ->where('unit_id', $unitId)
            ->first();

        return $productUnit ? (float) $productUnit->conversion_factor : 1.0;
    }

    /**
     * Get all available units for a product (base unit + alternate units).
     * Returns collection of ['unit_id' => int, 'unit_name' => string, 'conversion_factor' => float, 'is_purchase_unit' => bool, 'is_sales_unit' => bool]
     */
    public function getAvailableUnits(Product $product): Collection
    {
        $units = collect();

        // Add base unit
        if ($product->unit) {
            $units->push([
                'unit_id' => $product->unit_id,
                'unit_name' => $product->unit->name,
                'conversion_factor' => 1.0,
                'is_purchase_unit' => true,
                'is_sales_unit' => true,
                'is_base' => true,
            ]);
        }

        // Add alternate units
        $product->productUnits()->with('unit')->each(function ($pu) use ($units) {
            $units->push([
                'unit_id' => $pu->unit_id,
                'unit_name' => $pu->unit->name,
                'conversion_factor' => (float) $pu->conversion_factor,
                'is_purchase_unit' => $pu->is_purchase_unit,
                'is_sales_unit' => $pu->is_sales_unit,
                'is_base' => false,
            ]);
        });

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
