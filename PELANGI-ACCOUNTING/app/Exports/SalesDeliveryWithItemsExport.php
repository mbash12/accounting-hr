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
                        'Nomor Pengiriman' => $delivery->delivery_number,
                        'Tanggal' => $delivery->date ? $delivery->date->format('Y-m-d') : null,
                        'Jenis Pengiriman' => $delivery->delivery_type,
                        'Nomor Referensi' => $delivery->reference_no,
                        'Deskripsi' => $delivery->description,
                        'Status' => $delivery->status,
                        'Kode Customer' => $delivery->customer ? $delivery->customer->contact_code : null,
                        'Nama Customer' => $delivery->customer ? $delivery->customer->name : null,
                        'Nomor Pesanan Penjualan' => $delivery->salesOrder ? $delivery->salesOrder->order_number : null,
                        'Kode Produk' => $item->product ? $item->product->code : null,
                        'Nama Produk' => $item->product ? $item->product->name : null,
                        'Deskripsi Item' => $item->description,
                        'Jumlah' => $item->quantity,
                        'Kode Satuan' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If delivery has no items, still add the delivery row with empty item fields
                $results->push([
                    'Nomor Pengiriman' => $delivery->delivery_number,
                    'Tanggal' => $delivery->date ? $delivery->date->format('Y-m-d') : null,
                    'Jenis Pengiriman' => $delivery->delivery_type,
                    'Nomor Referensi' => $delivery->reference_no,
                    'Deskripsi' => $delivery->description,
                    'Status' => $delivery->status,
                    'Kode Customer' => $delivery->customer ? $delivery->customer->contact_code : null,
                    'Nama Customer' => $delivery->customer ? $delivery->customer->name : null,
                    'Nomor Pesanan Penjualan' => $delivery->salesOrder ? $delivery->salesOrder->order_number : null,
                    'Kode Produk' => null,
                    'Nama Produk' => null,
                    'Deskripsi Item' => null,
                    'Jumlah' => null,
                    'Kode Satuan' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Nomor Pengiriman',
            'Tanggal',
            'Jenis Pengiriman',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pesanan Penjualan',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Data Pengiriman Penjualan dan Item';
    }
}