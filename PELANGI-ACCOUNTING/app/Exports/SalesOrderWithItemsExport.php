<?php

namespace App\Exports;

use App\Models\SalesOrder;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesOrderWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = SalesOrder::with(['customer', 'items.product', 'items.unit', 'items.tax'])
            ->select([
                'id', 'order_number', 'date', 'reference_no', 'description',
                'discount_percentage', 'other_charges', 'discount', 'subtotal', 'tax_amount',
                'total_amount', 'status', 'customer_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $salesOrders = $query->get();
        foreach ($salesOrders as $salesOrder) {
            if ($salesOrder->items->count() > 0) {
                foreach ($salesOrder->items as $item) {
                    $results->push([
                        'Order No.' => $salesOrder->order_number,
                        'Date' => $salesOrder->date ? $salesOrder->date->format('Y-m-d') : null,
                        'Reference' => $salesOrder->reference_no,
                        'Order Description' => $salesOrder->description,
                        'Discount %' => $salesOrder->discount_percentage,
                        'Other Charges' => $salesOrder->other_charges,
                        'Discount' => $salesOrder->discount,
                        'Subtotal' => $salesOrder->subtotal,
                        'Tax' => $salesOrder->tax_amount,
                        'Total' => $salesOrder->total_amount,
                        'Status' => $salesOrder->status,
                        'Customer Code' => $salesOrder->customer ? $salesOrder->customer->contact_code : null,
                        'Customer Name' => $salesOrder->customer ? $salesOrder->customer->name : null,
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
                $results->push([
                    'Order No.' => $salesOrder->order_number,
                    'Date' => $salesOrder->date ? $salesOrder->date->format('Y-m-d') : null,
                    'Reference' => $salesOrder->reference_no,
                    'Order Description' => $salesOrder->description,
                    'Discount %' => $salesOrder->discount_percentage,
                    'Other Charges' => $salesOrder->other_charges,
                    'Discount' => $salesOrder->discount,
                    'Subtotal' => $salesOrder->subtotal,
                    'Tax' => $salesOrder->tax_amount,
                    'Total' => $salesOrder->total_amount,
                    'Status' => $salesOrder->status,
                    'Customer Code' => $salesOrder->customer ? $salesOrder->customer->contact_code : null,
                    'Customer Name' => $salesOrder->customer ? $salesOrder->customer->name : null,
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
            'Order No.',
            'Date',
            'Reference',
            'Order Description',
            'Discount %',
            'Other Charges',
            'Discount',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Customer Code',
            'Customer Name',
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
        return 'Sales Orders and Items';
    }
}
