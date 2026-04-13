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
                        'Nomor Faktur' => $invoice->invoice_number,
                        'Tanggal' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                        'Nomor Referensi' => $invoice->reference_no,
                        'Deskripsi' => $invoice->description,
                        'Biaya Lainnya' => $invoice->other_charges,
                        'Diskon' => $invoice->discount,
                        'Subtotal' => $invoice->subtotal,
                        'Pajak' => $invoice->tax_amount,
                        'Total' => $invoice->total_amount,
                        'Jumlah Dibayar' => $invoice->paid_amount,
                        'Jumlah Terhutang' => $invoice->outstanding_amount,
                        'Status' => $invoice->status,
                        'Kode Customer' => $invoice->customer ? $invoice->customer->contact_code : null,
                        'Nama Customer' => $invoice->customer ? $invoice->customer->name : null,
                        'Nomor Pesanan Penjualan' => $invoice->salesOrder ? $invoice->salesOrder->order_number : null,
                        'Kode Produk' => $item->product ? $item->product->code : null,
                        'Nama Produk' => $item->product ? $item->product->name : null,
                        'Deskripsi Item' => $item->description,
                        'Jumlah' => $item->quantity,
                        'Harga Satuan' => $item->unit_price,
                        'Total Item' => $item->total,
                        'Diskon Item' => $item->discount,
                        'Diskon Persen Item' => $item->discount_percentage,
                        'Pajak Item' => $item->tax_amount,
                        'Kode Satuan' => $item->unit ? $item->unit->code : null,
                        'Kode Pajak' => $item->tax ? $item->tax->code : null,
                    ]);
                }
            } else {
                // If invoice has no items, still add the invoice row with empty item fields
                $results->push([
                    'Nomor Faktur' => $invoice->invoice_number,
                    'Tanggal' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                    'Tanggal Jatuh Tempo' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                    'Nomor Referensi' => $invoice->reference_no,
                    'Deskripsi' => $invoice->description,
                    'Biaya Lainnya' => $invoice->other_charges,
                    'Diskon' => $invoice->discount,
                    'Subtotal' => $invoice->subtotal,
                    'Pajak' => $invoice->tax_amount,
                    'Total' => $invoice->total_amount,
                    'Jumlah Dibayar' => $invoice->paid_amount,
                    'Jumlah Terhutang' => $invoice->outstanding_amount,
                    'Status' => $invoice->status,
                    'Kode Customer' => $invoice->customer ? $invoice->customer->contact_code : null,
                    'Nama Customer' => $invoice->customer ? $invoice->customer->name : null,
                    'Nomor Pesanan Penjualan' => $invoice->salesOrder ? $invoice->salesOrder->order_number : null,
                    'Kode Produk' => null,
                    'Nama Produk' => null,
                    'Deskripsi Item' => null,
                    'Jumlah' => null,
                    'Harga Satuan' => null,
                    'Total Item' => null,
                    'Diskon Item' => null,
                    'Diskon Persen Item' => null,
                    'Pajak Item' => null,
                    'Kode Satuan' => null,
                    'Kode Pajak' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Nomor Faktur',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Subtotal',
            'Pajak',
            'Total',
            'Jumlah Dibayar',
            'Jumlah Terhutang',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pesanan Penjualan',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Harga Satuan',
            'Total Item',
            'Diskon Item',
            'Diskon Persen Item',
            'Pajak Item',
            'Kode Satuan',
            'Kode Pajak',
        ];
    }

    public function title(): string
    {
        return 'Data Faktur Penjualan dan Item';
    }
}