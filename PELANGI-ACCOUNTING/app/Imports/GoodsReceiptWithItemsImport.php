<?php

namespace App\Imports;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GoodsReceiptWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by receipt number to process receipts and their items together
        $receiptsData = [];

        foreach ($rows as $row) {
            $receiptNumber = (string) $row['no_penerimaan_barang'];

            if (!isset($receiptsData[$receiptNumber])) {
                // Create the receipt data
                $supplierId = null;
                if (!empty($row['kode_pemasok'])) {
                    $supplier = Contact::where('contact_code', (string) $row['kode_pemasok'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                    if (!$supplier) {
                        throw new \Exception("Supplier with code '{$row['kode_pemasok']}' not found in current company");
                    }
                    $supplierId = $supplier->id;
                } elseif (!empty($row['nama_pemasok'])) {
                    $supplier = Contact::where('name', (string) $row['nama_pemasok'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                    if (!$supplier) {
                        throw new \Exception("Supplier with name '{$row['nama_pemasok']}' not found in current company");
                    }
                    $supplierId = $supplier->id;
                } else {
                    throw new \Exception("Either Supplier Code or Supplier Name is required for receipt {$receiptNumber}");
                }

                $purchaseOrderId = null;
                if (!empty($row['nomor_pesanan_pembelian'])) {
                    $purchaseOrder = PurchaseOrder::where('purchase_order_no', (string) $row['nomor_pesanan_pembelian'])
                        ->where('company_id', $companyId)
                        ->where('supplier_id', $supplierId)
                        ->first();
                    if (!$purchaseOrder) {
                        throw new \Exception("Purchase Order with number '{$row['nomor_pesanan_pembelian']}' not found for supplier in current company for receipt {$receiptNumber}");
                    }
                    $purchaseOrderId = $purchaseOrder->id;
                }

                $receiptsData[$receiptNumber] = [
                    'receipt_data' => [
                        'receipt_number' => $receiptNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'purchase_order_id' => $purchaseOrderId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this receipt
            $productId = null;
            if (!empty($row['kode_produk'])) {
                $product = Product::where('code', (string) $row['kode_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['kode_produk']}' not found in current company for receipt {$receiptNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['nama_produk'])) {
                $product = Product::where('name', (string) $row['nama_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['nama_produk']}' not found in current company for receipt {$receiptNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Kode Produk or Nama Produk is required for receipt {$receiptNumber}");
            }

            $unitId = null;
            if (!empty($row['kode_satuan'])) {
                $unit = Unit::where('code', (string) $row['kode_satuan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['kode_satuan']}' not found in current company for receipt {$receiptNumber}");
                }
                $unitId = $unit->id;
            }

            $receiptsData[$receiptNumber]['items'][] = [
                'description' => isset($row['deskripsi_item']) ? (string) $row['deskripsi_item'] : null,
                'quantity' => isset($row['jumlah']) ? (float) $row['jumlah'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each receipt and its items
        foreach ($receiptsData as $receiptNumber => $receiptData) {
            $goodsReceipt = GoodsReceipt::where('receipt_number', $receiptNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($goodsReceipt) {
                // Update existing receipt
                $goodsReceipt->update($receiptData['receipt_data']);
                $goodsReceipt->items()->delete(); // Remove existing items to replace with new ones
            } else {
                // Create new receipt - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = GoodsReceipt::getEventDispatcher();
                GoodsReceipt::unsetEventDispatcher();

                try {
                    $goodsReceipt = new GoodsReceipt();
                    $goodsReceipt->forceFill($receiptData['receipt_data']);
                    $goodsReceipt->save();
                } finally {
                    // Re-enable model events
                    GoodsReceipt::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the receipt
            foreach ($receiptData['items'] as $itemData) {
                $itemData['goods_receipt_id'] = $goodsReceipt->id;
                GoodsReceiptItem::create($itemData);
            }

            // Refresh receipt tracking if there's a purchase order
            if ($goodsReceipt->purchase_order_id) {
                $goodsReceipt->purchaseOrder?->refreshReceiptTracking();
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'no_penerimaan_barang' => isset($data['no_penerimaan_barang']) ? (string) $data['no_penerimaan_barang'] : null,
            'tanggal' => isset($data['tanggal']) ? (string) $data['tanggal'] : null,
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_pemasok' => isset($data['kode_pemasok']) ? (string) $data['kode_pemasok'] : null,
            'nama_pemasok' => isset($data['nama_pemasok']) ? (string) $data['nama_pemasok'] : null,
            'nomor_pesanan_pembelian' => isset($data['nomor_pesanan_pembelian']) ? (string) $data['nomor_pesanan_pembelian'] : null,
            'kode_produk' => isset($data['kode_produk']) ? (string) $data['kode_produk'] : null,
            'nama_produk' => isset($data['nama_produk']) ? (string) $data['nama_produk'] : null,
            'deskripsi_item' => isset($data['deskripsi_item']) ? (string) $data['deskripsi_item'] : null,
            'jumlah' => isset($data['jumlah']) ? (string) $data['jumlah'] : null,
            'kode_satuan' => isset($data['kode_satuan']) ? (string) $data['kode_satuan'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'no_penerimaan_barang' => 'required|string|max:50',
            'tanggal' => 'required',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'kode_pemasok' => 'nullable|string|max:50',
            'nama_pemasok' => 'nullable|string|max:255',
            'nomor_pesanan_pembelian' => 'nullable|string|max:100',
            'kode_produk' => 'required_without:nama_produk|string|max:50',
            'nama_produk' => 'required_without:kode_produk|string|max:255',
            'deskripsi_item' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'kode_satuan' => 'nullable|string|max:20',
        ];
    }

    /**
     * Parse date from various formats that might come from Excel
     */
    private function parseDate($dateValue)
    {
        // If it's already in YYYY-MM-DD format, return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($dateValue))) {
            return trim($dateValue);
        }

        // Handle formats like 1/1/2024, 01/01/2024, etc.
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($dateValue), $matches)) {
            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return $year . '-' . $month . '-' . $day;
        }

        // Try to parse with Carbon for other formats
        try {
            $carbonDate = \Carbon\Carbon::parse($dateValue);
            return $carbonDate->format('Y-m-d');
        } catch (\Exception $e) {
            // If parsing fails, return current date
            return now()->format('Y-m-d');
        }
    }

    public function customValidationMessages()
    {
        return [
            'no_penerimaan_barang.required' => 'Goods Receipt Number is required.',
            'no_penerimaan_barang.max' => 'Goods Receipt Number cannot exceed 50 characters.',
            'tanggal.required' => 'Date is required.',
            'nomor_referensi.max' => 'Reference Number cannot exceed 100 characters.',
            'deskripsi.max' => 'Description cannot exceed 1000 characters.',
            'kode_pemasok.max' => 'Supplier Code cannot exceed 50 characters.',
            'nama_pemasok.max' => 'Supplier Name cannot exceed 255 characters.',
            'nomor_pesanan_pembelian.max' => 'Purchase Order Number cannot exceed 100 characters.',
            'kode_produk.max' => 'Product Code cannot exceed 50 characters.',
            'nama_produk.max' => 'Product Name cannot exceed 255 characters.',
            'jumlah.required' => 'Quantity is required.',
            'jumlah.min' => 'Quantity cannot be less than 0.',
            'jumlah.numeric' => 'Quantity must be a number.',
            'kode_satuan.max' => 'Unit Code cannot exceed 20 characters.',
        ];
    }
}