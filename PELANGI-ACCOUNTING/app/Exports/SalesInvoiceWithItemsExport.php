<?php

namespace App\Exports;

use App\Models\SalesInvoice;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesInvoiceWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = SalesInvoice::with(['customer', 'salesOrder', 'items.product', 'items.unit', 'items.tax'])
            ->select([
                'id', 'invoice_number', 'date', 'due_date', 'reference_no', 'description',
                'other_charges', 'discount', 'subtotal', 'tax_amount', 'total_amount',
                'paid_amount', 'outstanding_amount', 'status', 'customer_id', 'sales_order_id', 'company_id'
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
                        'Reference No.' => $invoice->reference_no,
                        'Description' => $invoice->description,
                        'Other Charges' => $invoice->other_charges,
                        'Discount' => $invoice->discount,
                        'Subtotal' => $invoice->subtotal,
                        'Tax' => $invoice->tax_amount,
                        'Total' => $invoice->total_amount,
                        'Paid Amount' => $invoice->paid_amount,
                        'Outstanding Amount' => $invoice->outstanding_amount,
                        'Status' => $invoice->status,
                        'Customer Code' => $invoice->customer ? $invoice->customer->contact_code : null,
                        'Customer Name' => $invoice->customer ? $invoice->customer->name : null,
                        'Sales Order No.' => $invoice->salesOrder ? $invoice->salesOrder->order_number : null,
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
                // If invoice has no items, still add the invoice row with empty item fields
                $results->push([
                    'Invoice No.' => $invoice->invoice_number,
                    'Date' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                    'Due Date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                    'Reference No.' => $invoice->reference_no,
                    'Description' => $invoice->description,
                    'Other Charges' => $invoice->other_charges,
                    'Discount' => $invoice->discount,
                    'Subtotal' => $invoice->subtotal,
                    'Tax' => $invoice->tax_amount,
                    'Total' => $invoice->total_amount,
                    'Paid Amount' => $invoice->paid_amount,
                    'Outstanding Amount' => $invoice->outstanding_amount,
                    'Status' => $invoice->status,
                    'Customer Code' => $invoice->customer ? $invoice->customer->contact_code : null,
                    'Customer Name' => $invoice->customer ? $invoice->customer->name : null,
                    'Sales Order No.' => $invoice->salesOrder ? $invoice->salesOrder->order_number : null,
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
            'Invoice No.',
            'Date',
            'Reference No.',
            'Description',
            'Other Charges',
            'Discount',
            'Subtotal',
            'Tax',
            'Total',
            'Paid Amount',
            'Outstanding Amount',
            'Status',
            'Customer Code',
            'Customer Name',
            'Sales Order No.',
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
        return 'Sales Invoices and Items';
    }
}
