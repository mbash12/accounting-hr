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
                        'Nomor Retur' => $return->return_number,
                        'Tanggal' => $return->date ? $return->date->format('Y-m-d') : null,
                        'Nomor Referensi' => $return->reference_no,
                        'Deskripsi' => $return->description,
                        'Status' => $return->status,
                        'Kode Pemasok' => $return->supplier ? $return->supplier->contact_code : null,
                        'Nama Pemasok' => $return->supplier ? $return->supplier->name : null,
                        'Nomor Penerimaan Barang' => $return->goodsReceipt ? $return->goodsReceipt->receipt_number : null,
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
                    'Kode Pemasok' => $return->supplier ? $return->supplier->contact_code : null,
                    'Nama Pemasok' => $return->supplier ? $return->supplier->name : null,
                    'Nomor Penerimaan Barang' => $return->goodsReceipt ? $return->goodsReceipt->receipt_number : null,
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
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Penerimaan Barang',
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
        return 'Data Retur Pembelian dan Item';
    }
}