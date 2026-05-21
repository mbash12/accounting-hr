<?php

namespace App\Exports;

use App\Models\PurchaseReturn;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseReturnWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = PurchaseReturn::with(['supplier', 'goodsReceipt', 'items.product', 'items.unit'])
            ->select([
                'id', 'return_number', 'date', 'reference_no', 'description',
                'status', 'supplier_id', 'goods_receipt_id', 'company_id'
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
                        'Supplier Code' => $return->supplier ? $return->supplier->contact_code : null,
                        'Supplier Name' => $return->supplier ? $return->supplier->name : null,
                        'Goods Receipt No.' => $return->goodsReceipt ? $return->goodsReceipt->receipt_number : null,
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
                    'Supplier Code' => $return->supplier ? $return->supplier->contact_code : null,
                    'Supplier Name' => $return->supplier ? $return->supplier->name : null,
                    'Goods Receipt No.' => $return->goodsReceipt ? $return->goodsReceipt->receipt_number : null,
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
            'Supplier Code',
            'Supplier Name',
            'Goods Receipt No.',
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
        return 'Purchase Returns and Items';
    }
}
