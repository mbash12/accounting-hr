<?php

namespace App\Exports;

use App\Models\GoodsReceipt;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GoodsReceiptWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = GoodsReceipt::with(['supplier', 'purchaseOrder', 'items.product', 'items.unit'])
            ->select([
                'id', 'receipt_number', 'date', 'reference_no', 'description',
                'status', 'supplier_id', 'purchase_order_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $receipts = $query->get();
        foreach ($receipts as $receipt) {
            if ($receipt->items->count() > 0) {
                foreach ($receipt->items as $item) {
                    $results->push([
                        'Receipt No.' => $receipt->receipt_number,
                        'Date' => $receipt->date ? $receipt->date->format('Y-m-d') : null,
                        'Reference No.' => $receipt->reference_no,
                        'Description' => $receipt->description,
                        'Status' => $receipt->status,
                        'Supplier Code' => $receipt->supplier ? $receipt->supplier->contact_code : null,
                        'Supplier Name' => $receipt->supplier ? $receipt->supplier->name : null,
                        'Purchase Order No.' => $receipt->purchaseOrder ? $receipt->purchaseOrder->purchase_order_no : null,
                        'Product Code' => $item->product ? $item->product->code : null,
                        'Product Name' => $item->product ? $item->product->name : null,
                        'Item Description' => $item->description,
                        'Quantity' => $item->quantity,
                        'Unit Code' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If receipt has no items, still add the receipt row with empty item fields
                $results->push([
                    'Receipt No.' => $receipt->receipt_number,
                    'Date' => $receipt->date ? $receipt->date->format('Y-m-d') : null,
                    'Reference No.' => $receipt->reference_no,
                    'Description' => $receipt->description,
                    'Status' => $receipt->status,
                    'Supplier Code' => $receipt->supplier ? $receipt->supplier->contact_code : null,
                    'Supplier Name' => $receipt->supplier ? $receipt->supplier->name : null,
                    'Purchase Order No.' => $receipt->purchaseOrder ? $receipt->purchaseOrder->purchase_order_no : null,
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
            'Receipt No.',
            'Date',
            'Reference No.',
            'Description',
            'Status',
            'Supplier Code',
            'Supplier Name',
            'Purchase Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Code',
        ];
    }

    public function title(): string
    {
        return 'Goods Receipts and Items';
    }
}
