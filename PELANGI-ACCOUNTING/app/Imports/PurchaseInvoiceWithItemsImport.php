<?php

namespace App\Imports;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\PurchaseOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PurchaseInvoiceWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by invoice number to process invoices and their items together
        $invoicesData = [];

        foreach ($rows as $row) {
            $invoiceNumber = (string) $row['nomor_faktur'];

            if (!isset($invoicesData[$invoiceNumber])) {
                // Create the invoice data
                $supplierId = null;
                $supplier = null;

                if (!empty($row['kode_pemasok'])) {
                    $supplier = Contact::where('contact_code', (string) $row['kode_pemasok'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                }

                if (!$supplier && !empty($row['nama_pemasok'])) {
                    $supplier = Contact::where('name', (string) $row['nama_pemasok'])
                        ->where('company_id', $companyId)
                        ->where('is_supplier', true)
                        ->first();
                }

                if (!$supplier) {
                    // Create supplier if not found
                    if (empty($row['nama_pemasok']) && empty($row['kode_pemasok'])) {
                         throw new \Exception("Either Kode Pemasok or Nama Pemasok is required for invoice {$invoiceNumber}");
                    }

                    $supplierData = [
                        'name' => (string) $row['nama_pemasok'] ?: (string) $row['kode_pemasok'],
                        'company_id' => $companyId,
                        'is_supplier' => true,
                        'created_by_user_id' => Auth::id(),
                    ];

                    if (!empty($row['kode_pemasok'])) {
                        $supplierData['contact_code'] = (string) $row['kode_pemasok'];
                    }

                    $supplier = Contact::create($supplierData);
                }
                $supplierId = $supplier->id;

                $purchaseOrderId = null;
                if (!empty($row['nomor_pesanan_pembelian'])) {
                    $purchaseOrder = PurchaseOrder::where('purchase_order_no', (string) $row['nomor_pesanan_pembelian'])
                        ->where('company_id', $companyId)
                        ->where('supplier_id', $supplierId)
                        ->first();
                    if ($purchaseOrder) {
                        $purchaseOrderId = $purchaseOrder->id;
                    }
                }

                $invoicesData[$invoiceNumber] = [
                    'invoice_data' => [
                        'invoice_number' => $invoiceNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'due_date' => isset($row['tanggal_jatuh_tempo']) ? $this->parseDate($row['tanggal_jatuh_tempo']) : null,
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'other_charges' => isset($row['biaya_lainnya']) ? (float) $row['biaya_lainnya'] : 0,
                        'discount' => isset($row['diskon']) ? (float) $row['diskon'] : 0,
                        'discount_percentage' => isset($row['diskon_persen']) ? (float) $row['diskon_persen'] : 0,
                        'subtotal' => isset($row['subtotal']) ? (float) $row['subtotal'] : 0,
                        'tax_amount' => isset($row['pajak']) ? (float) $row['pajak'] : 0,
                        'total' => isset($row['total']) ? (float) $row['total'] : 0,
                        'paid_amount' => isset($row['jumlah_dibayar']) ? (float) $row['jumlah_dibayar'] : 0,
                        'outstanding_amount' => isset($row['jumlah_terhutang']) ? (float) $row['jumlah_terhutang'] : 0,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'supplier_id' => $supplierId,
                        'purchase_order_id' => $purchaseOrderId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this invoice
            $productId = null;
            $product = null;

            if (!empty($row['kode_produk'])) {
                $product = Product::where('code', (string) $row['kode_produk'])
                    ->where('company_id', $companyId)
                    ->first();
            }

            if (!$product && !empty($row['nama_produk'])) {
                $product = Product::where('name', (string) $row['nama_produk'])
                    ->where('company_id', $companyId)
                    ->first();
            }

            if (!$product) {
                // Create product if it doesn't exist
                $productData = [
                    'name' => (string) $row['nama_produk'] ?: ((string) $row['kode_produk'] ?: 'New Product'),
                    'company_id' => $companyId,
                    'cost_price' => isset($row['harga_satuan']) ? (float) $row['harga_satuan'] : 0,
                    'created_by_user_id' => Auth::id(),
                ];

                if (!empty($row['kode_produk'])) {
                    $productData['code'] = (string) $row['kode_produk'];
                }

                $product = Product::create($productData);
            }
            $productId = $product->id;

            $unitId = null;
            if (!empty($row['kode_satuan'])) {
                $unit = Unit::where('code', (string) $row['kode_satuan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['kode_satuan']}' not found in current company for invoice {$invoiceNumber}");
                }
                $unitId = $unit->id;
            }

            $taxId = null;
            if (!empty($row['kode_pajak'])) {
                $tax = Tax::where('code', (string) $row['kode_pajak'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$tax) {
                    throw new \Exception("Tax with code '{$row['kode_pajak']}' not found in current company for invoice {$invoiceNumber}");
                }
                $taxId = $tax->id;
            }

            $invoicesData[$invoiceNumber]['items'][] = [
                'description' => isset($row['deskripsi_item']) ? (string) $row['deskripsi_item'] : null,
                'quantity' => isset($row['jumlah']) ? (float) $row['jumlah'] : 0,
                'unit_price' => isset($row['harga_satuan']) ? (float) $row['harga_satuan'] : 0,
                'total' => isset($row['total_item']) ? (float) $row['total_item'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'tax_id' => $taxId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each invoice and its items
        foreach ($invoicesData as $invoiceNumber => $invoiceData) {
            $purchaseInvoice = PurchaseInvoice::where('invoice_number', $invoiceNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($purchaseInvoice) {
                // Update existing invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseInvoice::getEventDispatcher();
                PurchaseInvoice::unsetEventDispatcher();

                try {
                    $purchaseInvoice->update($invoiceData['invoice_data']);
                    $purchaseInvoice->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    PurchaseInvoice::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = PurchaseInvoice::getEventDispatcher();
                PurchaseInvoice::unsetEventDispatcher();

                try {
                    $purchaseInvoice = new PurchaseInvoice();
                    $purchaseInvoice->forceFill($invoiceData['invoice_data']);
                    $purchaseInvoice->save();
                } finally {
                    // Re-enable model events
                    PurchaseInvoice::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the invoice
            foreach ($invoiceData['items'] as $itemData) {
                $itemData['purchase_invoice_id'] = $purchaseInvoice->id;
                PurchaseInvoiceItem::create($itemData);
            }

            // Calculate totals from items
            $items = $purchaseInvoice->items;
            $subtotal = $items->sum('total');
            $discountPercentage = $purchaseInvoice->discount_percentage ?? 0;
            $discount = $purchaseInvoice->discount ?? 0;
            $otherCharges = $purchaseInvoice->other_charges ?? 0;

            if ($discountPercentage > 0 && $subtotal > 0) {
                $discount = $subtotal * ($discountPercentage / 100);
            }

            // Calculate tax amount based on items with tax
            $taxAmount = 0;
            foreach ($items as $item) {
                if ($item->tax_id) {
                    $tax = Tax::find($item->tax_id);
                    if ($tax) {
                        $lineTotal = $item->total;
                        $lineDiscount = $lineTotal * ($discountPercentage / 100);
                        $taxBase = $lineTotal - $lineDiscount;
                        $taxAmount += $taxBase * ($tax->tax_percentage / 100);
                    }
                }
            }

            $totalAmount = $subtotal - $discount + $otherCharges + $taxAmount;
            $paidAmount = $purchaseInvoice->paid_amount ?? 0;
            $outstandingAmount = $totalAmount - $paidAmount;

            // Temporarily disable model events again for the totals update to avoid journal creation for draft status
            $dispatcher = PurchaseInvoice::getEventDispatcher();
            PurchaseInvoice::unsetEventDispatcher();

            try {
                $purchaseInvoice->update([
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'total' => $totalAmount,
                    'outstanding_amount' => $outstandingAmount,
                ]);
            } finally {
                // Re-enable model events
                PurchaseInvoice::setEventDispatcher($dispatcher);
            }

            // Refresh invoice tracking if there's a purchase order
            if ($purchaseInvoice->purchase_order_id) {
                $purchaseInvoice->purchaseOrder?->refreshInvoiceTracking();
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'nomor_faktur' => isset($data['nomor_faktur']) ? (string) $data['nomor_faktur'] : null,
            'tanggal' => isset($data['tanggal']) ? (string) $data['tanggal'] : null,
            'tanggal_jatuh_tempo' => isset($data['tanggal_jatuh_tempo']) ? (string) $data['tanggal_jatuh_tempo'] : null,
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'biaya_lainnya' => isset($data['biaya_lainnya']) ? (string) $data['biaya_lainnya'] : null,
            'diskon' => isset($data['diskon']) ? (string) $data['diskon'] : null,
            'diskon_persen' => isset($data['diskon_persen']) ? (string) $data['diskon_persen'] : null,
            'subtotal' => isset($data['subtotal']) ? (string) $data['subtotal'] : null,
            'pajak' => isset($data['pajak']) ? (string) $data['pajak'] : null,
            'total' => isset($data['total']) ? (string) $data['total'] : null,
            'jumlah_dibayar' => isset($data['jumlah_dibayar']) ? (string) $data['jumlah_dibayar'] : null,
            'jumlah_terhutang' => isset($data['jumlah_terhutang']) ? (string) $data['jumlah_terhutang'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_pemasok' => isset($data['kode_pemasok']) ? (string) $data['kode_pemasok'] : null,
            'nama_pemasok' => isset($data['nama_pemasok']) ? (string) $data['nama_pemasok'] : null,
            'nomor_pesanan_pembelian' => isset($data['nomor_pesanan_pembelian']) ? (string) $data['nomor_pesanan_pembelian'] : null,
            'kode_produk' => isset($data['kode_produk']) ? (string) $data['kode_produk'] : null,
            'nama_produk' => isset($data['nama_produk']) ? (string) $data['nama_produk'] : null,
            'deskripsi_item' => isset($data['deskripsi_item']) ? (string) $data['deskripsi_item'] : null,
            'jumlah' => isset($data['jumlah']) ? (string) $data['jumlah'] : null,
            'harga_satuan' => isset($data['harga_satuan']) ? (string) $data['harga_satuan'] : null,
            'total_item' => isset($data['total_item']) ? (string) $data['total_item'] : null,
            'kode_satuan' => isset($data['kode_satuan']) ? (string) $data['kode_satuan'] : null,
            'kode_pajak' => isset($data['kode_pajak']) ? (string) $data['kode_pajak'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'nomor_faktur' => 'required|string|max:50',
            'tanggal' => 'required',
            'tanggal_jatuh_tempo' => 'nullable',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'nullable|numeric|min:0',
            'pajak' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'jumlah_terhutang' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,posted',
            'kode_pemasok' => 'nullable|string|max:50',
            'nama_pemasok' => 'nullable|string|max:255',
            'nomor_pesanan_pembelian' => 'nullable|string|max:100',
            'kode_produk' => 'required_without:nama_produk|nullable|string|max:50',
            'nama_produk' => 'required_without:kode_produk|nullable|string|max:255',
            'deskripsi_item' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'total_item' => 'nullable|numeric|min:0',
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
            'nomor_faktur.required' => 'Nomor Faktur wajib diisi.',
            'nomor_faktur.max' => 'Nomor Faktur tidak boleh lebih dari 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal_jatuh_tempo.date' => 'Tanggal Jatuh Tempo harus berupa tanggal yang valid.',
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
            'jumlah_dibayar.min' => 'Jumlah Dibayar tidak boleh kurang dari 0.',
            'jumlah_dibayar.numeric' => 'Jumlah Dibayar harus berupa angka.',
            'jumlah_terhutang.min' => 'Jumlah Terhutang tidak boleh kurang dari 0.',
            'jumlah_terhutang.numeric' => 'Jumlah Terhutang harus berupa angka.',
            'kode_pemasok.max' => 'Kode Pemasok tidak boleh lebih dari 50 karakter.',
            'nama_pemasok.max' => 'Nama Pemasok tidak boleh lebih dari 255 karakter.',
            'nomor_pesanan_pembelian.max' => 'Nomor Pesanan Pembelian tidak boleh lebih dari 100 karakter.',
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
            'kode_satuan.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
            'kode_pajak.max' => 'Kode Pajak tidak boleh lebih dari 50 karakter.',
        ];
    }
}