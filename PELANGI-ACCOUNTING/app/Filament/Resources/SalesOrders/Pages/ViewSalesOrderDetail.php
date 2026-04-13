<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Models\SalesOrder;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesOrderDetail extends ViewRecord
{
    protected static string $resource = SalesOrderResource::class;

    protected string $view = 'filament.pages.sales-order-detail-view';

    public function getTitle(): string
    {
        return 'Detail Pesanan Penjualan';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Get all orders sharing the same job_number
     * This includes current order, related orders, and orders with same job_number
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRelatedOrders(): \Illuminate\Database\Eloquent\Collection
    {
        $jobNumber = $this->record->job_number;
        
        if (empty($jobNumber)) {
            // If no job_number, just return orders linked via related_order_id
            return SalesOrder::with(['items.product', 'customer'])
                ->where(function ($query) {
                    $query->where('related_order_id', $this->record->id)
                          ->orWhere('id', $this->record->related_order_id);
                })
                ->orWhere('id', $this->record->id)
                ->get()
                ->unique('id');
        }

        // Get all orders with the same job_number
        return SalesOrder::with(['items.product', 'customer'])
            ->where('job_number', $jobNumber)
            ->orWhere('id', $this->record->id)
            ->orWhere(function ($query) {
                // Also include orders linked via related_order_id even if job_number differs
                $query->where('related_order_id', $this->record->id)
                      ->orWhere('id', $this->record->related_order_id);
            })
            ->get()
            ->unique('id');
    }

    /**
     * Get all deposit orders related to this order
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDepositOrders(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getAllRelatedOrders()
            ->where('order_type', 'deposit')
            ->where('id', '!=', $this->record->id)
            ->values();
    }

    /**
     * Get all aktual orders related to this order
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAktualOrders(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getAllRelatedOrders()
            ->where('order_type', 'aktual')
            ->where('id', '!=', $this->record->id)
            ->values();
    }

    /**
     * Get related order (deposit <-> aktual) - DEPRECATED but kept for backward compatibility
     * Use getDepositOrders() or getAktualOrders() instead
     */
    public function getRelatedOrder(): ?SalesOrder
    {
        // If this SO has related_order_id, fetch it
        if ($this->record->related_order_id) {
            return SalesOrder::with(['items.product', 'customer'])
                ->find($this->record->related_order_id);
        }

        // If this SO is referenced by another SO (reverse relationship)
        return SalesOrder::with(['items.product', 'customer'])
            ->where('related_order_id', $this->record->id)
            ->first();
    }

    /**
     * Get purchase orders with items loaded
     */
    public function getPurchaseOrders(): \Illuminate\Database\Eloquent\Collection
    {
        // Get all related order IDs including current
        $soIds = $this->getAllRelatedOrders()->pluck('id')->toArray();
        
        if (empty($soIds)) {
            $soIds = [$this->record->id];
        }

        return \App\Models\PurchaseOrder::whereIn('sales_order_id', $soIds)
            ->with(['items.product', 'supplier'])
            ->get();
    }

    /**
     * Get sales invoices with items loaded
     */
    public function getSalesInvoices(): \Illuminate\Database\Eloquent\Collection
    {
        // Get all related order IDs including current
        $soIds = $this->getAllRelatedOrders()->pluck('id')->toArray();
        
        if (empty($soIds)) {
            $soIds = [$this->record->id];
        }

        return \App\Models\SalesInvoice::whereIn('sales_order_id', $soIds)
            ->with(['items.product', 'customer'])
            ->get();
    }

    /**
     * Get all invoices for this order group (all related orders)
     */
    public function getAllInvoices(): \Illuminate\Database\Eloquent\Collection
    {
        $soIds = $this->getAllRelatedOrders()->pluck('id')->toArray();
        
        if (empty($soIds)) {
            $soIds = [$this->record->id];
        }

        return \App\Models\SalesInvoice::whereIn('sales_order_id', array_unique($soIds))
            ->with(['items.product', 'customer'])
            ->get();
    }

    /**
     * Get all purchase orders for this order group (all related orders)
     */
    public function getAllPurchaseOrders(): \Illuminate\Database\Eloquent\Collection
    {
        $soIds = $this->getAllRelatedOrders()->pluck('id')->toArray();
        
        if (empty($soIds)) {
            $soIds = [$this->record->id];
        }

        return \App\Models\PurchaseOrder::whereIn('sales_order_id', array_unique($soIds))
            ->with(['items.product', 'supplier'])
            ->get();
    }

    /**
     * Get SO items with product and related PO items
     */
    public function getSalesOrderItemsWithComparison(): array
    {
        // Get all aktual orders for comparison
        $aktualOrders = $this->getAktualOrders();

        // If current order is aktual, include it
        $targetSos = $this->record->order_type === 'aktual'
            ? $aktualOrders->push($this->record)->unique('id')
            : $aktualOrders;

        if ($targetSos->isEmpty()) {
            return [];
        }

        $soItems = $targetSos->flatMap(function ($so) {
            return $so->items()->with(['product', 'unit'])->get();
        });

        $purchaseOrders = $this->getAllPurchaseOrders();
        $poItems = $purchaseOrders->flatMap->items;

        $comparison = [];

        foreach ($soItems as $soItem) {
            $productId = $soItem->product_id;

            // Find matching PO items for the same product
            $matchingPoItems = $poItems->where('product_id', $productId);

            $poData = [];
            $totalPoQty = 0;
            $totalPoAmount = 0;
            $avgPoPrice = 0;

            foreach ($matchingPoItems as $poItem) {
                $poData[] = [
                    'po_id' => $poItem->purchaseOrder?->id,
                    'po_number' => $poItem->purchaseOrder?->purchase_order_no ?? '-',
                    'supplier' => $poItem->purchaseOrder?->supplier?->name ?? '-',
                    'quantity' => $poItem->quantity,
                    'unit_price' => $poItem->unit_price,
                    'total' => $poItem->total,
                ];
                $totalPoQty += $poItem->quantity;
                $totalPoAmount += $poItem->total;
            }

            if ($totalPoQty > 0) {
                $avgPoPrice = $totalPoAmount / $totalPoQty;
            }

            // Calculate margin
            $soPrice = $soItem->unit_price;
            $margin = $soPrice - $avgPoPrice;
            $marginPercent = $avgPoPrice > 0 ? ($margin / $avgPoPrice) * 100 : 0;

            $comparison[] = [
                'so_item' => $soItem,
                'so_order_number' => $soItem->salesOrder?->order_number ?? '-',
                'po_items' => $poData,
                'so_price' => $soPrice,
                'avg_po_price' => $avgPoPrice,
                'margin' => $margin,
                'margin_percent' => $marginPercent,
                'total_po_qty' => $totalPoQty,
                'total_po_amount' => $totalPoAmount,
            ];
        }

        return $comparison;
    }

    /**
     * Get ALL SO items with product and related PO items (standard and actual only)
     * This compares SO items from standard/actual orders (ignoring deposit) with their PO counterparts
     */
    public function getAllSalesOrderItemsWithPoComparison(): array
    {
        $allRelatedOrders = $this->getAllRelatedOrders();

        if ($allRelatedOrders->isEmpty()) {
            return [];
        }

        // Get all SO items from standard and actual orders only (ignore deposit)
        $allSoItems = $allRelatedOrders
            ->filter(function ($so) {
                return in_array($so->order_type, ['standar', 'aktual']);
            })
            ->flatMap(function ($so) {
                return $so->items()->with(['product', 'unit', 'salesOrder'])->get();
            });

        // Group SO items by product_id to aggregate quantities and prices
        $groupedSoItems = $allSoItems->groupBy('product_id');

        // Get all PO items
        $purchaseOrders = $this->getAllPurchaseOrders();
        $allPoItems = $purchaseOrders->flatMap->items;

        // Group PO items by product_id
        $groupedPoItems = $allPoItems->groupBy('product_id');

        $comparison = [];

        foreach ($groupedSoItems as $productId => $soItemsGroup) {
            $product = $soItemsGroup->first()->product;

            // Calculate aggregated SO data
            $totalSoQty = $soItemsGroup->sum('quantity');
            $totalSoAmount = $soItemsGroup->sum('total');
            $avgSoPrice = $totalSoQty > 0 ? $totalSoAmount / $totalSoQty : 0;

            // Get PO items for this product
            $poItemsGroup = $groupedPoItems->get($productId, collect());

            $poData = [];
            $totalPoQty = 0;
            $totalPoAmount = 0;
            $avgPoPrice = 0;

            foreach ($poItemsGroup as $poItem) {
                $poData[] = [
                    'po_id' => $poItem->purchaseOrder?->id,
                    'po_number' => $poItem->purchaseOrder?->purchase_order_no ?? '-',
                    'supplier' => $poItem->purchaseOrder?->supplier?->name ?? '-',
                    'quantity' => $poItem->quantity,
                    'unit_price' => $poItem->unit_price,
                    'total' => $poItem->total,
                ];
                $totalPoQty += $poItem->quantity;
                $totalPoAmount += $poItem->total;
            }

            if ($totalPoQty > 0) {
                $avgPoPrice = $totalPoAmount / $totalPoQty;
            }

            // Calculate gap/margin
            $gapAmount = $avgSoPrice - $avgPoPrice;
            $gapPercent = $avgPoPrice > 0 ? (($avgSoPrice - $avgPoPrice) / $avgPoPrice) * 100 : 0;
            $gapTotalAmount = ($avgSoPrice * $totalSoQty) - ($avgPoPrice * $totalSoQty);

            // Get SO order numbers for reference
            $soOrderNumbers = $soItemsGroup->map(function ($item) {
                return $item->salesOrder?->order_number ?? '-';
            })->unique()->values()->toArray();

            $comparison[] = [
                'product' => $product,
                'product_id' => $productId,
                'so_order_numbers' => $soOrderNumbers,
                'so_qty' => $totalSoQty,
                'so_avg_price' => $avgSoPrice,
                'so_total_amount' => $totalSoAmount,
                'po_items' => $poData,
                'po_qty' => $totalPoQty,
                'po_avg_price' => $avgPoPrice,
                'po_total_amount' => $totalPoAmount,
                'gap_amount' => $gapAmount,
                'gap_percent' => $gapPercent,
                'gap_total_amount' => $gapTotalAmount,
                'has_po' => $totalPoQty > 0,
            ];
        }

        return $comparison;
    }

    /**
     * Get summary data for all related orders
     */
    public function getSummaryData(): array
    {
        $allOrders = $this->getAllRelatedOrders();
        
        // Calculate totals for each order type
        $depositOrders = $allOrders->where('order_type', 'deposit');
        $aktualOrders = $allOrders->where('order_type', 'aktual');
        $standarOrders = $allOrders->where('order_type', 'standar');

        $depositTotal = $depositOrders->sum('total_amount');
        $aktualTotal = $aktualOrders->sum('total_amount');
        $standarTotal = $standarOrders->sum('total_amount');
        
        // Total SO (all types)
        $soTotal = $allOrders->sum('total_amount');
        
        // Get PO and invoice totals
        $poTotal = $this->getAllPurchaseOrders()->sum('total_amount');
        $invoicedTotal = $this->getAllInvoices()->sum('total_amount');
        
        return [
            'so_total' => $soTotal,
            'deposit_total' => $depositTotal,
            'deposit_count' => $depositOrders->count(),
            'aktual_total' => $aktualTotal,
            'aktual_count' => $aktualOrders->count(),
            'standar_total' => $standarTotal,
            'standar_count' => $standarOrders->count(),
            'po_total' => $poTotal,
            'invoiced_total' => $invoicedTotal,
            'gross_profit' => $soTotal - $poTotal,
            'profit_margin' => $soTotal > 0 ? (($soTotal - $poTotal) / $soTotal) * 100 : 0,
        ];
    }

    /**
     * Check if this is a linked order (has related orders via job_number or related_order_id)
     */
    public function isLinkedOrder(): bool
    {
        return $this->getAllRelatedOrders()->count() > 1;
    }

    /**
     * Get order statistics
     */
    public function getOrderStats(): array
    {
        $allOrders = $this->getAllRelatedOrders();
        
        return [
            'total_orders' => $allOrders->count(),
            'deposit_orders' => $allOrders->where('order_type', 'deposit')->count(),
            'aktual_orders' => $allOrders->where('order_type', 'aktual')->count(),
            'standar_orders' => $allOrders->where('order_type', 'standar')->count(),
            'total_items' => $allOrders->sum(function ($order) {
                return $order->items->count();
            }),
        ];
    }
}
