<?php

namespace App\Imports;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\GoodsReceipt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PurchaseReturnWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by return number to process returns and their items together
        $returnsData = [];

        foreach ($rows as $row) {
            $returnNumber = (string) $row['nomor_retur'];

            if (!isset($returnsData[$returnNumber])) {
                // Create the return data
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
                    throw new \Exception("Either Kode Pemasok or Nama Pemasok is required for return {$returnNumber}");
                }

                $goodsReceiptId = null;
                if (!empty($row['nomor_penerimaan_barang'])) {
                    $goodsReceipt = GoodsReceipt::where('receipt_number', (string) $row['nomor_penerimaan_barang'])
                        ->where('company_id', $companyId)
                        ->where('supplier_id', $supplierId)
                        ->first();
                    if (!$goodsReceipt) {
                        throw new \Exception("Goods Receipt with number '{$row['nomor_penerimaan_barang']}' not found for supplier in current company for return {$returnNumber}");
                    }
                    $goodsReceiptId = $goodsReceipt->id;
                }

                $returnsData[$returnNumber] = [
                    'return_data' => [
                        'return_number' => $returnNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'goods_receipt_id' => $goodsReceiptId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this return
            $productId = null;
            if (!empty($row['kode_produk'])) {
                $product = Product::where('code', (string) $row['kode_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['kode_produk']}' not found in current company for return {$returnNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['nama_produk'])) {
                $product = Product::where('name', (string) $row['nama_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['nama_produk']}' not found in current company for return {$returnNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Kode Produk or Nama Produk is required for return {$returnNumber}");
            }

            $unitId = null;
            if (!empty($row['kode_satuan'])) {
                $unit = Unit::where('code', (string) $row['kode_satuan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['kode_satuan']}' not found in current company for return {$returnNumber}");
                }
                $unitId = $unit->id;
            }

            $returnsData[$returnNumber]['items'][] = [
                'description' => isset($row['deskripsi_item']) ? (string) $row['deskripsi_item'] : null,
                'quantity' => isset($row['jumlah']) ? (float) $row['jumlah'] : 0,
                'return_reason' => isset($row['alasan_retur']) ? (string) $row['alasan_retur'] : null,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each return and its items
        foreach ($returnsData as $returnNumber => $returnData) {
            $purchaseReturn = PurchaseReturn::where('return_number', $returnNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($purchaseReturn) {
                // Update existing return
                $purchaseReturn->update($returnData['return_data']);
                $purchaseReturn->items()->delete(); // Remove existing items to replace with new ones
            } else {
                // Create new return - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseReturn::getEventDispatcher();
                PurchaseReturn::unsetEventDispatcher();

                try {
                    $purchaseReturn = new PurchaseReturn();
                    $purchaseReturn->forceFill($returnData['return_data']);
                    $purchaseReturn->save();
                } finally {
                    // Re-enable model events
                    PurchaseReturn::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the return
            foreach ($returnData['items'] as $itemData) {
                $itemData['purchase_return_id'] = $purchaseReturn->id;
                PurchaseReturnItem::create($itemData);
            }

            // Refresh return tracking if there's a goods receipt
            if ($purchaseReturn->goods_receipt_id) {
                $purchaseReturn->goodsReceipt?->refreshReturnTracking();
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'nomor_retur' => isset($data['nomor_retur']) ? (string) $data['nomor_retur'] : null,
            'tanggal' => isset($data['tanggal']) ? (string) $data['tanggal'] : null,
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_pemasok' => isset($data['kode_pemasok']) ? (string) $data['kode_pemasok'] : null,
            'nama_pemasok' => isset($data['nama_pemasok']) ? (string) $data['nama_pemasok'] : null,
            'nomor_penerimaan_barang' => isset($data['nomor_penerimaan_barang']) ? (string) $data['nomor_penerimaan_barang'] : null,
            'kode_produk' => isset($data['kode_produk']) ? (string) $data['kode_produk'] : null,
            'nama_produk' => isset($data['nama_produk']) ? (string) $data['nama_produk'] : null,
            'deskripsi_item' => isset($data['deskripsi_item']) ? (string) $data['deskripsi_item'] : null,
            'jumlah' => isset($data['jumlah']) ? (string) $data['jumlah'] : null,
            'alasan_retur' => isset($data['alasan_retur']) ? (string) $data['alasan_retur'] : null,
            'kode_satuan' => isset($data['kode_satuan']) ? (string) $data['kode_satuan'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'nomor_retur' => 'required|string|max:50',
            'tanggal' => 'required',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'kode_pemasok' => 'nullable|string|max:50',
            'nama_pemasok' => 'nullable|string|max:255',
            'nomor_penerimaan_barang' => 'nullable|string|max:100',
            'kode_produk' => 'required_without:nama_produk|string|max:50',
            'nama_produk' => 'required_without:kode_produk|string|max:255',
            'deskripsi_item' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'alasan_retur' => 'required|string|max:255',
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
            'nomor_retur.required' => 'Nomor Retur wajib diisi.',
            'nomor_retur.max' => 'Nomor Retur tidak boleh lebih dari 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'nomor_referensi.max' => 'Nomor Referensi tidak boleh lebih dari 100 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'kode_pemasok.max' => 'Kode Pemasok tidak boleh lebih dari 50 karakter.',
            'nama_pemasok.max' => 'Nama Pemasok tidak boleh lebih dari 255 karakter.',
            'nomor_penerimaan_barang.max' => 'Nomor Penerimaan Barang tidak boleh lebih dari 100 karakter.',
            'kode_produk.max' => 'Kode Produk tidak boleh lebih dari 50 karakter.',
            'nama_produk.max' => 'Nama Produk tidak boleh lebih dari 255 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'alasan_retur.required' => 'Alasan Retur wajib diisi.',
            'alasan_retur.max' => 'Alasan Retur tidak boleh lebih dari 255 karakter.',
            'kode_satuan.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
        ];
    }
}