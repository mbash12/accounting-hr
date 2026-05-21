<?php

namespace App\Exports;

use App\Models\PurchaseInvoice;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseInvoiceWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = PurchaseInvoice::with(['supplier', 'purchaseOrder', 'items.product', 'items.unit', 'items.tax'])
            ->select([
                'id', 'invoice_number', 'date', 'due_date', 'reference_no', 'description',
                'other_charges', 'discount', 'discount_percentage', 'subtotal', 'tax_amount', 'total',
                'paid_amount', 'outstanding_amount', 'status', 'supplier_id', 'purchase_order_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $invoices = $query->get();
        foreach ($invoices as $invoice) {
            if ($invoice->items->count() > 0) {
                foreach ($invoice->items as $item) {
                    $results->push([
                        'Invoice No.' => $invoice->invoice_number,
                        'Date' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                        'Due Date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                        'Reference No.' => $invoice->reference_no,
                        'Description' => $invoice->description,
                        'Other Charges' => $invoice->other_charges,
                        'Discount' => $invoice->discount,
                        'Discount %' => $invoice->discount_percentage,
                        'Subtotal' => $invoice->subtotal,
                        'Tax' => $invoice->tax_amount,
                        'Total' => $invoice->total,
                        'Paid Amount' => $invoice->paid_amount,
                        'Outstanding Amount' => $invoice->outstanding_amount,
                        'Status' => $invoice->status,
                        'Supplier Code' => $invoice->supplier ? $invoice->supplier->contact_code : null,
                        'Supplier Name' => $invoice->supplier ? $invoice->supplier->name : null,
                        'Purchase Order No.' => $invoice->purchaseOrder ? $invoice->purchaseOrder->purchase_order_no : null,
                        'Product Code' => $item->product ? $item->product->code : null,
                        'Product Name' => $item->product ? $item->product->name : null,
                        'Item Description' => $item->description,
                        'Quantity' => $item->quantity,
                        'Unit Price' => $item->unit_price,
                        'Item Total' => $item->total,
                        'Unit Code' => $item->unit ? $item->unit->code : null,
                        'Tax Code' => $item->tax ? $item->tax->code : null,
                    ]);
                }
            } else {
                // If invoice has no items, still add the invoice row with empty item fields
                $results->push([
                    'Invoice No.' => $invoice->invoice_number,
                    'Date' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                    'Due Date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                    'Reference No.' => $invoice->reference_no,
                    'Description' => $invoice->description,
                    'Other Charges' => $invoice->other_charges,
                    'Discount' => $invoice->discount,
                    'Discount %' => $invoice->discount_percentage,
                    'Subtotal' => $invoice->subtotal,
                    'Tax' => $invoice->tax_amount,
                    'Total' => $invoice->total,
                    'Paid Amount' => $invoice->paid_amount,
                    'Outstanding Amount' => $invoice->outstanding_amount,
                    'Status' => $invoice->status,
                    'Supplier Code' => $invoice->supplier ? $invoice->supplier->contact_code : null,
                    'Supplier Name' => $invoice->supplier ? $invoice->supplier->name : null,
                    'Purchase Order No.' => $invoice->purchaseOrder ? $invoice->purchaseOrder->purchase_order_no : null,
                    'Product Code' => null,
                    'Product Name' => null,
                    'Item Description' => null,
                    'Quantity' => null,
                    'Unit Price' => null,
                    'Item Total' => null,
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
            'Invoice No.',
            'Date',
            'Due Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Discount %',
            'Subtotal',
            'Tax',
            'Total',
            'Paid Amount',
            'Outstanding Amount',
            'Status',
            'Supplier Code',
            'Supplier Name',
            'Purchase Order No.',
            'Product Code',
            'Product Name',
            'Item Description',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Unit Code',
            'Tax Code',
        ];
    }

    public function title(): string
    {
        return 'Purchase Invoices and Items';
    }
}
