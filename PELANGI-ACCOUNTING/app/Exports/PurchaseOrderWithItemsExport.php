<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseOrderWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = PurchaseOrder::with(['supplier', 'items.product', 'items.unit', 'items.tax'])
            ->select([
                'id', 'purchase_order_no', 'date', 'reference_no', 'description',
                'other_charges', 'discount', 'discount_percentage', 'subtotal', 'tax_amount', 'total',
                'total_amount', 'status', 'supplier_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $orders = $query->get();
        foreach ($orders as $order) {
            if ($order->items->count() > 0) {
                foreach ($order->items as $item) {
                    $results->push([
                        'Purchase Order No.' => $order->purchase_order_no,
                        'Date' => $order->date ? $order->date->format('Y-m-d') : null,
                        'Reference No.' => $order->reference_no,
                        'Description' => $order->description,
                        'Other Charges' => $order->other_charges,
                        'Discount' => $order->discount,
                        'Discount %' => $order->discount_percentage,
                        'Subtotal' => $order->subtotal,
                        'Tax' => $order->tax_amount,
                        'Total' => $order->total_amount,
                        'Status' => $order->status,
                        'Supplier Code' => $order->supplier ? $order->supplier->contact_code : null,
                        'Supplier Name' => $order->supplier ? $order->supplier->name : null,
                        'Product Code' => $item->product ? $item->product->code : null,
                        'Product Name' => $item->product ? $item->product->name : null,
                        'Item Description' => $item->description,
                        'Quantity' => $item->quantity,
                        'Unit Price' => $item->unit_price,
                        'Item Total' => $item->total,
                        'Item Discount' => $item->discount,
                        'Item Discount %' => $item->discount_percentage,
                        'Item Tax' => $item->tax_amount,
                        'Unit Code' => $item->unit ? $item->unit->code : null,
                        'Tax Code' => $item->tax ? $item->tax->code : null,
                    ]);
                }
            } else {
                // If order has no items, still add the order row with empty item fields
                $results->push([
                    'Purchase Order No.' => $order->purchase_order_no,
                    'Date' => $order->date ? $order->date->format('Y-m-d') : null,
                    'Reference No.' => $order->reference_no,
                    'Description' => $order->description,
                    'Other Charges' => $order->other_charges,
                    'Discount' => $order->discount,
                    'Discount %' => $order->discount_percentage,
                    'Subtotal' => $order->subtotal,
                    'Tax' => $order->tax_amount,
                    'Total' => $order->total_amount,
                    'Status' => $order->status,
                    'Supplier Code' => $order->supplier ? $order->supplier->contact_code : null,
                    'Supplier Name' => $order->supplier ? $order->supplier->name : null,
                    'Product Code' => null,
                    'Product Name' => null,
                    'Item Description' => null,
                    'Quantity' => null,
                    'Unit Price' => null,
                    'Item Total' => null,
                    'Item Discount' => null,
                    'Item Discount %' => null,
                    'Item Tax' => null,
                    'Unit Code' => null,
                    'Tax Code' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Purchase Order No.',
            'Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Discount %',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Supplier Code',
            'Supplier Name',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Item Discount',
            'Item Discount %',
            'Item Tax',
            'Unit Code',
            'Tax Code',
        ];
    }

    public function title(): string
    {
        return 'Purchase Orders and Items';
    }
}
