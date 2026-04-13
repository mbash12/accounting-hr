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
                        'Nomor Faktur' => $invoice->invoice_number,
                        'Tanggal' => $invoice->date ? $invoice->date->format('Y-m-d') : null,
                        'Tanggal Jatuh Tempo' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                        'Nomor Referensi' => $invoice->reference_no,
                        'Deskripsi' => $invoice->description,
                        'Biaya Lainnya' => $invoice->other_charges,
                        'Diskon' => $invoice->discount,
                        'Diskon Persen' => $invoice->discount_percentage,
                        'Subtotal' => $invoice->subtotal,
                        'Pajak' => $invoice->tax_amount,
                        'Total' => $invoice->total,
                        'Jumlah Dibayar' => $invoice->paid_amount,
                        'Jumlah Terhutang' => $invoice->outstanding_amount,
                        'Status' => $invoice->status,
                        'Kode Pemasok' => $invoice->supplier ? $invoice->supplier->contact_code : null,
                        'Nama Pemasok' => $invoice->supplier ? $invoice->supplier->name : null,
                        'Nomor Pesanan Pembelian' => $invoice->purchaseOrder ? $invoice->purchaseOrder->purchase_order_no : null,
                        'Kode Produk' => $item->product ? $item->product->code : null,
                        'Nama Produk' => $item->product ? $item->product->name : null,
                        'Deskripsi Item' => $item->description,
                        'Jumlah' => $item->quantity,
                        'Harga Satuan' => $item->unit_price,
                        'Total Item' => $item->total,
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
                    'Diskon Persen' => $invoice->discount_percentage,
                    'Subtotal' => $invoice->subtotal,
                    'Pajak' => $invoice->tax_amount,
                    'Total' => $invoice->total,
                    'Jumlah Dibayar' => $invoice->paid_amount,
                    'Jumlah Terhutang' => $invoice->outstanding_amount,
                    'Status' => $invoice->status,
                    'Kode Pemasok' => $invoice->supplier ? $invoice->supplier->contact_code : null,
                    'Nama Pemasok' => $invoice->supplier ? $invoice->supplier->name : null,
                    'Nomor Pesanan Pembelian' => $invoice->purchaseOrder ? $invoice->purchaseOrder->purchase_order_no : null,
                    'Kode Produk' => null,
                    'Nama Produk' => null,
                    'Deskripsi Item' => null,
                    'Jumlah' => null,
                    'Harga Satuan' => null,
                    'Total Item' => null,
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
            'Tanggal Jatuh Tempo',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Diskon Persen',
            'Subtotal',
            'Pajak',
            'Total',
            'Jumlah Dibayar',
            'Jumlah Terhutang',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Pesanan Pembelian',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Harga Satuan',
            'Total Item',
            'Kode Satuan',
            'Kode Pajak',
        ];
    }

    public function title(): string
    {
        return 'Data Faktur Pembelian dan Item';
    }
}