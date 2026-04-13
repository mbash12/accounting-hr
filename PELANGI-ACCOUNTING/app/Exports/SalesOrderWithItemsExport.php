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
                'id', 'order_number', 'order_type', 'date', 'reference_no', 'description',
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
                        'Nomor Pesanan' => $salesOrder->order_number,
                        'Tanggal' => $salesOrder->date ? $salesOrder->date->format('Y-m-d') : null,
                        'Tipe Pesanan' => $salesOrder->order_type,
                        'Referensi' => $salesOrder->reference_no,
                        'Deskripsi Pesanan' => $salesOrder->description,
                        'Diskon Persen' => $salesOrder->discount_percentage,
                        'Biaya Lainnya' => $salesOrder->other_charges,
                        'Diskon' => $salesOrder->discount,
                        'Subtotal' => $salesOrder->subtotal,
                        'Pajak' => $salesOrder->tax_amount,
                        'Total' => $salesOrder->total_amount,
                        'Status' => $salesOrder->status,
                        'Kode Customer' => $salesOrder->customer ? $salesOrder->customer->contact_code : null,
                        'Nama Customer' => $salesOrder->customer ? $salesOrder->customer->name : null,
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
                    'Nomor Pesanan' => $salesOrder->order_number,
                    'Tanggal' => $salesOrder->date ? $salesOrder->date->format('Y-m-d') : null,
                    'Tipe Pesanan' => $salesOrder->order_type,
                    'Referensi' => $salesOrder->reference_no,
                    'Deskripsi Pesanan' => $salesOrder->description,
                    'Diskon Persen' => $salesOrder->discount_percentage,
                    'Biaya Lainnya' => $salesOrder->other_charges,
                    'Diskon' => $salesOrder->discount,
                    'Subtotal' => $salesOrder->subtotal,
                    'Pajak' => $salesOrder->tax_amount,
                    'Total' => $salesOrder->total_amount,
                    'Status' => $salesOrder->status,
                    'Kode Customer' => $salesOrder->customer ? $salesOrder->customer->contact_code : null,
                    'Nama Customer' => $salesOrder->customer ? $salesOrder->customer->name : null,
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
            'Nomor Pesanan',
            'Tanggal',
            'Tipe Pesanan',
            'Referensi',
            'Deskripsi Pesanan',
            'Diskon Persen',
            'Biaya Lainnya',
            'Diskon',
            'Subtotal',
            'Pajak',
            'Total',
            'Status',
            'Kode Customer',
            'Nama Customer',
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
        return 'Data Pesanan Penjualan dan Item';
    }
}