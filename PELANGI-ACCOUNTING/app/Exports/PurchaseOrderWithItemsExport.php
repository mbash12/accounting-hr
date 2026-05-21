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
                        'No. Pesanan Pembelian' => $order->purchase_order_no,
                        'Tanggal' => $order->date ? $order->date->format('Y-m-d') : null,
                        'Nomor Referensi' => $order->reference_no,
                        'Deskripsi' => $order->description,
                        'Biaya Lainnya' => $order->other_charges,
                        'Diskon' => $order->discount,
                        'Diskon Persen' => $order->discount_percentage,
                        'Subtotal' => $order->subtotal,
                        'Pajak' => $order->tax_amount,
                        'Total' => $order->total_amount,
                        'Status' => $order->status,
                        'Kode Pemasok' => $order->supplier ? $order->supplier->contact_code : null,
                        'Nama Pemasok' => $order->supplier ? $order->supplier->name : null,
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
                // If order has no items, still add the order row with empty item fields
                $results->push([
                    'No. Pesanan Pembelian' => $order->purchase_order_no,
                    'Tanggal' => $order->date ? $order->date->format('Y-m-d') : null,
                    'Nomor Referensi' => $order->reference_no,
                    'Deskripsi' => $order->description,
                    'Biaya Lainnya' => $order->other_charges,
                    'Diskon' => $order->discount,
                    'Diskon Persen' => $order->discount_percentage,
                    'Subtotal' => $order->subtotal,
                    'Pajak' => $order->tax_amount,
                    'Total' => $order->total_amount,
                    'Status' => $order->status,
                    'Kode Pemasok' => $order->supplier ? $order->supplier->contact_code : null,
                    'Nama Pemasok' => $order->supplier ? $order->supplier->name : null,
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
            'No. Pesanan Pembelian',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Biaya Lainnya',
            'Diskon',
            'Diskon Persen',
            'Subtotal',
            'Pajak',
            'Total',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
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
        return 'Data Pesanan Pembelian dan Item';
    }
}