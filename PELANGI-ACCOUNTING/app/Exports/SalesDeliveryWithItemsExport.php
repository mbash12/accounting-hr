<?php

namespace App\Exports;

use App\Models\DeliveryDocument;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesDeliveryWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = DeliveryDocument::with(['customer', 'salesOrder', 'items.product', 'items.unit'])
            ->select([
                'id', 'delivery_number', 'delivery_type', 'date', 'reference_no', 'description',
                'status', 'customer_id', 'sales_order_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $deliveries = $query->get();
        foreach ($deliveries as $delivery) {
            if ($delivery->items->count() > 0) {
                foreach ($delivery->items as $item) {
                    $results->push([
                        'Delivery No.' => $delivery->delivery_number,
                        'Date' => $delivery->date ? $delivery->date->format('Y-m-d') : null,
                        'Delivery Type' => $delivery->delivery_type,
                        'Reference No.' => $delivery->reference_no,
                        'Description' => $delivery->description,
                        'Status' => $delivery->status,
                        'Customer Code' => $delivery->customer ? $delivery->customer->contact_code : null,
                        'Customer Name' => $delivery->customer ? $delivery->customer->name : null,
                        'Sales Order No.' => $delivery->salesOrder ? $delivery->salesOrder->order_number : null,
                        'Product Code' => $item->product ? $item->product->code : null,
                        'Product Name' => $item->product ? $item->product->name : null,
                        'Item Description' => $item->description,
                        'Quantity' => $item->quantity,
                        'Unit Code' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If delivery has no items, still add the delivery row with empty item fields
                $results->push([
                    'Delivery No.' => $delivery->delivery_number,
                    'Date' => $delivery->date ? $delivery->date->format('Y-m-d') : null,
                    'Delivery Type' => $delivery->delivery_type,
                    'Reference No.' => $delivery->reference_no,
                    'Description' => $delivery->description,
                    'Status' => $delivery->status,
                    'Customer Code' => $delivery->customer ? $delivery->customer->contact_code : null,
                    'Customer Name' => $delivery->customer ? $delivery->customer->name : null,
                    'Sales Order No.' => $delivery->salesOrder ? $delivery->salesOrder->order_number : null,
                    'Product Code' => null,
                    'Product Name' => null,
                    'Item Description' => null,
                    'Quantity' => null,
                    'Unit Code' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Delivery No.',
            'Date',
            'Delivery Type',
            'Reference No.',
            'Description',
            'Status',
            'Customer Code',
            'Customer Name',
            'Sales Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Sales Deliveries and Items';
    }
}
