<?php

namespace App\Imports;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PurchaseOrderWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by purchase order number to process orders and their items together
        $ordersData = [];

        foreach ($rows as $row) {
            $orderNumber = (string) $row['no_pesanan_pembelian'];

            if (!isset($ordersData[$orderNumber])) {
                // Create the order data
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
                    throw new \Exception("Either Kode Pemasok or Nama Pemasok is required for order {$orderNumber}");
                }

                $departmentId = null;
                if (!empty($row['kode_departemen'])) {
                    $department = Department::where('code', (string) $row['kode_departemen'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$department) {
                        throw new \Exception("Department with code '{$row['kode_departemen']}' not found in current company for order {$orderNumber}");
                    }
                    $departmentId = $department->id;
                } elseif (!empty($row['nama_departemen'])) {
                    $department = Department::where('name', (string) $row['nama_departemen'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$department) {
                        throw new \Exception("Department with name '{$row['nama_departemen']}' not found in current company for order {$orderNumber}");
                    }
                    $departmentId = $department->id;
                }

                $ordersData[$orderNumber] = [
                    'order_data' => [
                        'purchase_order_no' => $orderNumber,
                        'order_type' => 'standar',
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'other_charges' => isset($row['biaya_lainnya']) ? (float) $row['biaya_lainnya'] : 0,
                        'discount' => isset($row['diskon']) ? (float) $row['diskon'] : 0,
                        'discount_percentage' => isset($row['diskon_persen']) ? (float) $row['diskon_persen'] : 0,
                        'subtotal' => isset($row['subtotal']) ? (float) $row['subtotal'] : 0,
                        'tax_amount' => isset($row['pajak']) ? (float) $row['pajak'] : 0,
                        'total' => isset($row['total']) ? (float) $row['total'] : 0,
                        'total_amount' => isset($row['total']) ? (float) $row['total'] : 0,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'department_id' => $departmentId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this order
            $productId = null;
            if (!empty($row['kode_produk'])) {
                $product = Product::where('code', (string) $row['kode_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['kode_produk']}' not found in current company for order {$orderNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['nama_produk'])) {
                $product = Product::where('name', (string) $row['nama_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['nama_produk']}' not found in current company for order {$orderNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Kode Produk or Nama Produk is required for order {$orderNumber}");
            }

            $unitId = null;
            if (!empty($row['kode_satuan'])) {
                $unit = Unit::where('code', (string) $row['kode_satuan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['kode_satuan']}' not found in current company for order {$orderNumber}");
                }
                $unitId = $unit->id;
            }

            $taxId = null;
            if (!empty($row['kode_pajak'])) {
                $tax = Tax::where('code', (string) $row['kode_pajak'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$tax) {
                    throw new \Exception("Tax with code '{$row['kode_pajak']}' not found in current company for order {$orderNumber}");
                }
                $taxId = $tax->id;
            }

            $ordersData[$orderNumber]['items'][] = [
                'description' => isset($row['deskripsi_item']) ? (string) $row['deskripsi_item'] : null,
                'quantity' => isset($row['jumlah']) ? (float) $row['jumlah'] : 0,
                'unit_price' => isset($row['harga_satuan']) ? (float) $row['harga_satuan'] : 0,
                'total' => isset($row['total_item']) ? (float) $row['total_item'] : 0,
                'discount' => isset($row['diskon_item']) ? (float) $row['diskon_item'] : 0,
                'discount_percentage' => isset($row['diskon_persen_item']) ? (float) $row['diskon_persen_item'] : 0,
                'tax_amount' => isset($row['pajak_item']) ? (float) $row['pajak_item'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'tax_id' => $taxId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each order and its items
        foreach ($ordersData as $orderNumber => $orderData) {
            $purchaseOrder = PurchaseOrder::where('purchase_order_no', $orderNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($purchaseOrder) {
                // Update existing order - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseOrder::getEventDispatcher();
                PurchaseOrder::unsetEventDispatcher();

                try {
                    $purchaseOrder->update($orderData['order_data']);
                    $purchaseOrder->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    PurchaseOrder::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new order - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseOrder::getEventDispatcher();
                PurchaseOrder::unsetEventDispatcher();

                try {
                    $purchaseOrder = new PurchaseOrder();
                    $purchaseOrder->forceFill($orderData['order_data']);
                    $purchaseOrder->save();
                } finally {
                    // Re-enable model events
                    PurchaseOrder::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the order
            foreach ($orderData['items'] as $itemData) {
                $itemData['purchase_order_id'] = $purchaseOrder->id;
                PurchaseOrderItem::create($itemData);
            }

            // Recalculate totals after all items are added
            $purchaseOrder->recalculateTotalsFromItems();

            // Temporarily disable model events again for the totals update to avoid journal creation for draft status
            $dispatcher = PurchaseOrder::getEventDispatcher();
            PurchaseOrder::unsetEventDispatcher();

            try {
                $purchaseOrder->saveQuietly();
            } finally {
                // Re-enable model events
                PurchaseOrder::setEventDispatcher($dispatcher);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'no_pesanan_pembelian' => isset($data['no_pesanan_pembelian']) ? (string) $data['no_pesanan_pembelian'] : null,
            'tanggal' => isset($data['tanggal']) ? (string) $data['tanggal'] : null,
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'biaya_lainnya' => isset($data['biaya_lainnya']) ? (string) $data['biaya_lainnya'] : null,
            'diskon' => isset($data['diskon']) ? (string) $data['diskon'] : null,
            'diskon_persen' => isset($data['diskon_persen']) ? (string) $data['diskon_persen'] : null,
            'subtotal' => isset($data['subtotal']) ? (string) $data['subtotal'] : null,
            'pajak' => isset($data['pajak']) ? (string) $data['pajak'] : null,
            'total' => isset($data['total']) ? (string) $data['total'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_pemasok' => isset($data['kode_pemasok']) ? (string) $data['kode_pemasok'] : null,
            'nama_pemasok' => isset($data['nama_pemasok']) ? (string) $data['nama_pemasok'] : null,
            'kode_departemen' => isset($data['kode_departemen']) ? (string) $data['kode_departemen'] : null,
            'nama_departemen' => isset($data['nama_departemen']) ? (string) $data['nama_departemen'] : null,
            'kode_produk' => isset($data['kode_produk']) ? (string) $data['kode_produk'] : null,
            'nama_produk' => isset($data['nama_produk']) ? (string) $data['nama_produk'] : null,
            'deskripsi_item' => isset($data['deskripsi_item']) ? (string) $data['deskripsi_item'] : null,
            'jumlah' => isset($data['jumlah']) ? (string) $data['jumlah'] : null,
            'harga_satuan' => isset($data['harga_satuan']) ? (string) $data['harga_satuan'] : null,
            'total_item' => isset($data['total_item']) ? (string) $data['total_item'] : null,
            'diskon_item' => isset($data['diskon_item']) ? (string) $data['diskon_item'] : null,
            'diskon_persen_item' => isset($data['diskon_persen_item']) ? (string) $data['diskon_persen_item'] : null,
            'pajak_item' => isset($data['pajak_item']) ? (string) $data['pajak_item'] : null,
            'kode_satuan' => isset($data['kode_satuan']) ? (string) $data['kode_satuan'] : null,
            'kode_pajak' => isset($data['kode_pajak']) ? (string) $data['kode_pajak'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'no_pesanan_pembelian' => 'required|string|max:50',
            'tanggal' => 'required',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'nullable|numeric|min:0',
            'pajak' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,posted',
            'kode_pemasok' => 'nullable|string|max:50',
            'nama_pemasok' => 'nullable|string|max:255',
            'kode_departemen' => 'nullable|string|max:50',
            'nama_departemen' => 'nullable|string|max:255',
            'kode_produk' => 'required_without:nama_produk|string|max:50',
            'nama_produk' => 'required_without:kode_produk|string|max:255',
            'deskripsi_item' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'total_item' => 'nullable|numeric|min:0',
            'diskon_item' => 'nullable|numeric|min:0',
            'diskon_persen_item' => 'nullable|numeric|min:0|max:100',
            'pajak_item' => 'nullable|numeric|min:0',
            'kode_satuan' => 'nullable|string|max:20',
            'kode_pajak' => 'nullable|string|max:50',
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
            'no_pesanan_pembelian.required' => 'No. Pesanan Pembelian wajib diisi.',
            'no_pesanan_pembelian.max' => 'No. Pesanan Pembelian tidak boleh lebih dari 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'nomor_referensi.max' => 'Nomor Referensi tidak boleh lebih dari 100 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'biaya_lainnya.min' => 'Biaya Lainnya tidak boleh kurang dari 0.',
            'biaya_lainnya.numeric' => 'Biaya Lainnya harus berupa angka.',
            'diskon.min' => 'Diskon tidak boleh kurang dari 0.',
            'diskon.numeric' => 'Diskon harus berupa angka.',
            'diskon_persen.min' => 'Diskon Persen tidak boleh kurang dari 0.',
            'diskon_persen.max' => 'Diskon Persen tidak boleh lebih dari 100.',
            'diskon_persen.numeric' => 'Diskon Persen harus berupa angka.',
            'subtotal.min' => 'Subtotal tidak boleh kurang dari 0.',
            'subtotal.numeric' => 'Subtotal harus berupa angka.',
            'pajak.min' => 'Pajak tidak boleh kurang dari 0.',
            'pajak.numeric' => 'Pajak harus berupa angka.',
            'total.min' => 'Total tidak boleh kurang dari 0.',
            'total.numeric' => 'Total harus berupa angka.',
            'kode_pemasok.max' => 'Kode Pemasok tidak boleh lebih dari 50 karakter.',
            'nama_pemasok.max' => 'Nama Pemasok tidak boleh lebih dari 255 karakter.',
            'kode_departemen.max' => 'Kode Departemen tidak boleh lebih dari 50 karakter.',
            'nama_departemen.max' => 'Nama Departemen tidak boleh lebih dari 255 karakter.',
            'kode_produk.max' => 'Kode Produk tidak boleh lebih dari 50 karakter.',
            'nama_produk.max' => 'Nama Produk tidak boleh lebih dari 255 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'harga_satuan.required' => 'Harga Satuan wajib diisi.',
            'harga_satuan.min' => 'Harga Satuan tidak boleh kurang dari 0.',
            'harga_satuan.numeric' => 'Harga Satuan harus berupa angka.',
            'total_item.min' => 'Total Item tidak boleh kurang dari 0.',
            'total_item.numeric' => 'Total Item harus berupa angka.',
            'diskon_item.min' => 'Diskon Item tidak boleh kurang dari 0.',
            'diskon_item.numeric' => 'Diskon Item harus berupa angka.',
            'diskon_persen_item.min' => 'Diskon Persen Item tidak boleh kurang dari 0.',
            'diskon_persen_item.max' => 'Diskon Persen Item tidak boleh lebih dari 100.',
            'diskon_persen_item.numeric' => 'Diskon Persen Item harus berupa angka.',
            'pajak_item.min' => 'Pajak Item tidak boleh kurang dari 0.',
            'pajak_item.numeric' => 'Pajak Item harus berupa angka.',
            'kode_satuan.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
            'kode_pajak.max' => 'Kode Pajak tidak boleh lebih dari 50 karakter.',
        ];
    }
}