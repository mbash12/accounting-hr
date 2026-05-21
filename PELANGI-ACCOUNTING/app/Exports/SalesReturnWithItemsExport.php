<?php

namespace App\Exports;

use App\Models\SalesReturn;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReturnWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = SalesReturn::with(['customer', 'deliveryDocument', 'items.product', 'items.unit'])
            ->select([
                'id', 'return_number', 'date', 'reference_no', 'description',
                'status', 'customer_id', 'delivery_document_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $returns = $query->get();
        foreach ($returns as $return) {
            if ($return->items->count() > 0) {
                foreach ($return->items as $item) {
                    $results->push([
                        'Return No.' => $return->return_number,
                        'Date' => $return->date ? $return->date->format('Y-m-d') : null,
                        'Reference No.' => $return->reference_no,
                        'Description' => $return->description,
                        'Status' => $return->status,
                        'Customer Code' => $return->customer ? $return->customer->contact_code : null,
                        'Customer Name' => $return->customer ? $return->customer->name : null,
                        'Delivery No.' => $return->deliveryDocument ? $return->deliveryDocument->delivery_number : null,
                        'Product Code' => $item->product ? $item->product->code : null,
                        'Product Name' => $item->product ? $item->product->name : null,
                        'Item Description' => $item->description,
                        'Quantity' => $item->quantity,
                        'Return Reason' => $item->return_reason,
                        'Unit Code' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If return has no items, still add the return row with empty item fields
                $results->push([
                    'Return No.' => $return->return_number,
                    'Date' => $return->date ? $return->date->format('Y-m-d') : null,
                    'Reference No.' => $return->reference_no,
                    'Description' => $return->description,
                    'Status' => $return->status,
                    'Customer Code' => $return->customer ? $return->customer->contact_code : null,
                    'Customer Name' => $return->customer ? $return->customer->name : null,
                    'Delivery No.' => $return->deliveryDocument ? $return->deliveryDocument->delivery_number : null,
                    'Product Code' => null,
                    'Product Name' => null,
                    'Item Description' => null,
                    'Quantity' => null,
                    'Return Reason' => null,
                    'Unit Code' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Return No.',
            'Date',
            'Reference No.',
            'Description',
            'Status',
            'Customer Code',
            'Customer Name',
            'Delivery No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Return Reason',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Sales Returns and Items';
    }
}
