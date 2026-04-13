<?php

namespace App\Exports;

use App\Models\GoodsReceipt;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GoodsReceiptWithItemsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = GoodsReceipt::with(['supplier', 'purchaseOrder', 'items.product', 'items.unit'])
            ->select([
                'id', 'receipt_number', 'date', 'reference_no', 'description',
                'status', 'supplier_id', 'purchase_order_id', 'company_id'
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $results = collect();

        $receipts = $query->get();
        foreach ($receipts as $receipt) {
            if ($receipt->items->count() > 0) {
                foreach ($receipt->items as $item) {
                    $results->push([
                        'No. Penerimaan Barang' => $receipt->receipt_number,
                        'Tanggal' => $receipt->date ? $receipt->date->format('Y-m-d') : null,
                        'Nomor Referensi' => $receipt->reference_no,
                        'Deskripsi' => $receipt->description,
                        'Status' => $receipt->status,
                        'Kode Pemasok' => $receipt->supplier ? $receipt->supplier->contact_code : null,
                        'Nama Pemasok' => $receipt->supplier ? $receipt->supplier->name : null,
                        'Nomor Pesanan Pembelian' => $receipt->purchaseOrder ? $receipt->purchaseOrder->purchase_order_no : null,
                        'Kode Produk' => $item->product ? $item->product->code : null,
                        'Nama Produk' => $item->product ? $item->product->name : null,
                        'Deskripsi Item' => $item->description,
                        'Jumlah' => $item->quantity,
                        'Kode Satuan' => $item->unit ? $item->unit->code : null,
                    ]);
                }
            } else {
                // If receipt has no items, still add the receipt row with empty item fields
                $results->push([
                    'No. Penerimaan Barang' => $receipt->receipt_number,
                    'Tanggal' => $receipt->date ? $receipt->date->format('Y-m-d') : null,
                    'Nomor Referensi' => $receipt->reference_no,
                    'Deskripsi' => $receipt->description,
                    'Status' => $receipt->status,
                    'Kode Pemasok' => $receipt->supplier ? $receipt->supplier->contact_code : null,
                    'Nama Pemasok' => $receipt->supplier ? $receipt->supplier->name : null,
                    'Nomor Pesanan Pembelian' => $receipt->purchaseOrder ? $receipt->purchaseOrder->purchase_order_no : null,
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
            'No. Penerimaan Barang',
            'Tanggal',
            'Nomor Referensi',
            'Deskripsi',
            'Status',
            'Kode Pemasok',
            'Nama Pemasok',
            'Nomor Pesanan Pembelian',
            'Kode Produk',
            'Nama Produk',
            'Deskripsi Item',
            'Jumlah',
            'Kode Satuan',
        ];
    }

    public function title(): string
    {
        return 'Data Penerimaan Barang dan Item';
    }
}