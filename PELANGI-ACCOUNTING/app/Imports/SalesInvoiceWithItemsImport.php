<?php

namespace App\Imports;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesInvoiceWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
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
                $customerId = null;
                $customer = null;

                if (!empty($row['kode_customer'])) {
                    $customer = Contact::where('contact_code', (string) $row['kode_customer'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                }

                if (!$customer && !empty($row['nama_customer'])) {
                    $customer = Contact::where('name', (string) $row['nama_customer'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                }

                if (!$customer) {
                    // Create customer if not found
                    if (empty($row['nama_customer']) && empty($row['kode_customer'])) {
                         throw new \Exception("Either Kode Customer or Nama Customer is required for invoice {$invoiceNumber}");
                    }

                    $customerData = [
                        'name' => (string) $row['nama_customer'] ?: (string) $row['kode_customer'],
                        'company_id' => $companyId,
                        'is_customer' => true,
                        'created_by_user_id' => Auth::id(),
                    ];

                    if (!empty($row['kode_customer'])) {
                        $customerData['contact_code'] = (string) $row['kode_customer'];
                    }

                    $customer = Contact::create($customerData);
                }
                $customerId = $customer->id;

                $salesOrderId = null;
                if (!empty($row['nomor_pesanan_penjualan'])) {
                    $salesOrder = \App\Models\SalesOrder::where('order_number', (string) $row['nomor_pesanan_penjualan'])
                        ->where('company_id', $companyId)
                        ->first();
                    if ($salesOrder) {
                        $salesOrderId = $salesOrder->id;
                    }
                }

                $invoicesData[$invoiceNumber] = [
                    'invoice_data' => [
                        'invoice_number' => $invoiceNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'other_charges' => isset($row['biaya_lainnya']) ? (float) $row['biaya_lainnya'] : 0,
                        'discount' => isset($row['diskon']) ? (float) $row['diskon'] : 0,
                        'subtotal' => isset($row['subtotal']) ? (float) $row['subtotal'] : 0,
                        'tax_amount' => isset($row['pajak']) ? (float) $row['pajak'] : 0,
                        'total_amount' => isset($row['total']) ? (float) $row['total'] : 0,
                        'paid_amount' => isset($row['jumlah_dibayar']) ? (float) $row['jumlah_dibayar'] : 0,
                        'outstanding_amount' => isset($row['jumlah_terhutang']) ? (float) $row['jumlah_terhutang'] : 0,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'sales_order_id' => $salesOrderId,
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
                    'selling_price' => isset($row['harga_satuan']) ? (float) $row['harga_satuan'] : 0,
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
                'item_name' => (string) $row['nama_produk'] ?: $product->name,
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

        // Process each invoice and its items
        foreach ($invoicesData as $invoiceNumber => $invoiceData) {
            $salesInvoice = SalesInvoice::where('invoice_number', $invoiceNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($salesInvoice) {
                // Update existing invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesInvoice::getEventDispatcher();
                SalesInvoice::unsetEventDispatcher();

                try {
                    $salesInvoice->update($invoiceData['invoice_data']);
                    $salesInvoice->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    SalesInvoice::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new invoice - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesInvoice::getEventDispatcher();
                SalesInvoice::unsetEventDispatcher();

                try {
                    $salesInvoice = new SalesInvoice();
                    $salesInvoice->forceFill($invoiceData['invoice_data']);
                    $salesInvoice->save();
                } finally {
                    // Re-enable model events
                    SalesInvoice::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the invoice
            foreach ($invoiceData['items'] as $itemData) {
                $itemData['sales_invoice_id'] = $salesInvoice->id;
                SalesInvoiceItem::create($itemData);
            }

            // Calculate totals from items
            $items = $salesInvoice->items;
            $subtotal = $items->sum('total');
            $discount = $salesInvoice->discount ?? 0;
            $otherCharges = $salesInvoice->other_charges ?? 0;

            // Calculate tax amount based on items with tax
            $taxAmount = 0;
            foreach ($items as $item) {
                if ($item->tax_id) {
                    $tax = Tax::find($item->tax_id);
                    if ($tax) {
                        $taxAmount += $item->total * ($tax->tax_percentage / 100);
                    }
                }
            }

            $totalAmount = $subtotal - $discount + $otherCharges + $taxAmount;
            $paidAmount = $salesInvoice->paid_amount ?? 0;
            $outstandingAmount = $totalAmount - $paidAmount;

            // Temporarily disable model events again for the totals update to avoid journal creation for draft status
            $dispatcher = SalesInvoice::getEventDispatcher();
            SalesInvoice::unsetEventDispatcher();

            try {
                $salesInvoice->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'outstanding_amount' => $outstandingAmount,
                ]);
            } finally {
                // Re-enable model events
                SalesInvoice::setEventDispatcher($dispatcher);
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
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'biaya_lainnya' => isset($data['biaya_lainnya']) ? (string) $data['biaya_lainnya'] : null,
            'diskon' => isset($data['diskon']) ? (string) $data['diskon'] : null,
            'subtotal' => isset($data['subtotal']) ? (string) $data['subtotal'] : null,
            'pajak' => isset($data['pajak']) ? (string) $data['pajak'] : null,
            'total' => isset($data['total']) ? (string) $data['total'] : null,
            'jumlah_dibayar' => isset($data['jumlah_dibayar']) ? (string) $data['jumlah_dibayar'] : null,
            'jumlah_terhutang' => isset($data['jumlah_terhutang']) ? (string) $data['jumlah_terhutang'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_customer' => isset($data['kode_customer']) ? (string) $data['kode_customer'] : null,
            'nama_customer' => isset($data['nama_customer']) ? (string) $data['nama_customer'] : null,
            'nomor_pesanan_penjualan' => isset($data['nomor_pesanan_penjualan']) ? (string) $data['nomor_pesanan_penjualan'] : null,
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
            'nomor_faktur' => 'required|string|max:50',
            'tanggal' => 'required',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'biaya_lainnya' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'subtotal' => 'nullable|numeric|min:0',
            'pajak' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'jumlah_terhutang' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,posted',
            'kode_customer' => 'nullable|string|max:50',
            'nama_customer' => 'nullable|string|max:255',
            'nomor_pesanan_penjualan' => 'nullable|string|max:100',
            'kode_produk' => 'required_without:nama_produk|nullable|string|max:50',
            'nama_produk' => 'required_without:kode_produk|nullable|string|max:255',
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
            'nomor_faktur.required' => 'Nomor Faktur wajib diisi.',
            'nomor_faktur.max' => 'Nomor Faktur tidak boleh lebih dari 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'nomor_referensi.max' => 'Nomor Referensi tidak boleh lebih dari 100 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'biaya_lainnya.min' => 'Biaya Lainnya tidak boleh kurang dari 0.',
            'biaya_lainnya.numeric' => 'Biaya Lainnya harus berupa angka.',
            'diskon.min' => 'Diskon tidak boleh kurang dari 0.',
            'diskon.numeric' => 'Diskon harus berupa angka.',
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
            'kode_customer.max' => 'Kode Customer tidak boleh lebih dari 50 karakter.',
            'nama_customer.max' => 'Nama Customer tidak boleh lebih dari 255 karakter.',
            'nomor_pesanan_penjualan.max' => 'Nomor Pesanan Penjualan tidak boleh lebih dari 100 karakter.',
            'kode_produk.max' => 'Kode Produk tidak boleh lebih dari 50 karakter.',
            'nama_produk.max' => 'Nama Produk tidak boleh lebih from 255 karakter.',
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
            'kode_satuan.max' => 'Kode Satuan tidak boleh lebih from 20 karakter.',
            'kode_pajak.max' => 'Kode Pajak tidak boleh lebih from 50 karakter.',
        ];
    }
}
