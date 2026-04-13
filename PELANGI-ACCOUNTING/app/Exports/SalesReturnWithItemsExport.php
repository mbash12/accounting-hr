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
                        'Nomor Retur' => $return->return_number,
                        'Tanggal' => $return->date ? $return->date->format('Y-m-d') : null,
                        'Nomor Referensi' => $return->reference_no,
                        'Deskripsi' => $return->description,
                        'Status' => $return->status,
                        'Kode Customer' => $return->customer ? $return->customer->contact_code : null,
                        'Nama Customer' => $return->customer ? $return->customer->name : null,
                        'Nomor Pengiriman' => $return->deliveryDocument ? $return->deliveryDocument->delivery_number : null,
                        'Kode Produk' => $item->product ? $item->product->code : null,
                        'Nama Produk' => $item->product ? $item->product->name : null,
                        'Deskripsi Item' => $item->description,
                        'Jumlah' => $item->quantity,
                        'Alasan Retur' => $item->return_reason,
                        'Kode Satuan' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If return has no items, still add the return row with empty item fields
                $results->push([
                    'Nomor Retur' => $return->return_number,
                    'Tanggal' => $return->date ? $return->date->format('Y-m-d') : null,
                    'Nomor Referensi' => $return->reference_no,
                    'Deskripsi' => $return->description,
                    'Status' => $return->status,
                    'Kode Customer' => $return->customer ? $return->customer->contact_code : null,
                    'Nama Customer' => $return->customer ? $return->customer->name : null,
                    'Nomor Pengiriman' => $return->deliveryDocument ? $return->deliveryDocument->delivery_number : null,
                    'Kode Produk' => null,
                    'Nama Produk' => null,
                    'Deskripsi Item' => null,
                    'Jumlah' => null,
                    'Alasan Retur' => null,
                    'Kode Satuan' => null,
                ]);
            }
        }

        return $results;
    }

    public function headings(): array
    {
        return [
            'Nomor Retur',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Customer',
            'Nama Customer',
            'Nomor Pengiriman',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Alasan Retur',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Data Retur Penjualan dan Item';
    }
}