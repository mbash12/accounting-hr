<?php

namespace App\Imports;

use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Project;
use App\Models\DeliveryDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesReturnWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
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
                $customerId = null;
                if (!empty($row['kode_customer'])) {
                    $customer = Contact::where('contact_code', (string) $row['kode_customer'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                    if (!$customer) {
                        throw new \Exception("Customer with code '{$row['kode_customer']}' not found or is not marked as customer in current company");
                    }
                    $customerId = $customer->id;
                } elseif (!empty($row['nama_customer'])) {
                    $customer = Contact::where('name', (string) $row['nama_customer'])
                        ->where('company_id', $companyId)
                        ->where('is_customer', true)
                        ->first();
                    if (!$customer) {
                        throw new \Exception("Customer with name '{$row['nama_customer']}' not found or is not marked as customer in current company");
                    }
                    $customerId = $customer->id;
                } else {
                    throw new \Exception("Either Customer Code or Customer Name is required for return {$returnNumber}");
                }

                $deliveryDocumentId = null;
                if (!empty($row['nomor_pengiriman'])) {
                    $deliveryDocument = DeliveryDocument::where('delivery_number', (string) $row['nomor_pengiriman'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$deliveryDocument) {
                        throw new \Exception("Delivery Document with number '{$row['nomor_pengiriman']}' not found in current company for return {$returnNumber}");
                    }
                    $deliveryDocumentId = $deliveryDocument->id;
                }

                $returnsData[$returnNumber] = [
                    'return_data' => [
                        'return_number' => $returnNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'delivery_document_id' => $deliveryDocumentId,
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
                throw new \Exception("Either Product Code or Product Name is required for return {$returnNumber}");
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
            $salesReturn = SalesReturn::where('return_number', $returnNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($salesReturn) {
                // Update existing return
                $salesReturn->update($returnData['return_data']);
                $salesReturn->items()->delete(); // Remove existing items to replace with new ones
            } else {
                // Create new return - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = SalesReturn::getEventDispatcher();
                SalesReturn::unsetEventDispatcher();

                try {
                    $salesReturn = new SalesReturn();
                    $salesReturn->forceFill($returnData['return_data']);
                    $salesReturn->save();
                } finally {
                    // Re-enable model events
                    SalesReturn::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the return
            foreach ($returnData['items'] as $itemData) {
                $itemData['sales_return_id'] = $salesReturn->id;
                SalesReturnItem::create($itemData);
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
            'kode_customer' => isset($data['kode_customer']) ? (string) $data['kode_customer'] : null,
            'nama_customer' => isset($data['nama_customer']) ? (string) $data['nama_customer'] : null,
            'nomor_pengiriman' => isset($data['nomor_pengiriman']) ? (string) $data['nomor_pengiriman'] : null,
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
            'kode_customer' => 'nullable|string|max:50',
            'nama_customer' => 'nullable|string|max:255',
            'nomor_pengiriman' => 'nullable|string|max:100',
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
            'nomor_retur.required' => 'Return Number is required.',
            'nomor_retur.max' => 'Return Number cannot exceed 50 characters.',
            'tanggal.required' => 'Date is required.',
            'nomor_referensi.max' => 'Reference Number cannot exceed 100 characters.',
            'deskripsi.max' => 'Description cannot exceed 1000 characters.',
            'kode_customer.max' => 'Customer Code cannot exceed 50 characters.',
            'nama_customer.max' => 'Customer Name cannot exceed 255 characters.',
            'nomor_pengiriman.max' => 'Delivery Number cannot exceed 100 characters.',
            'kode_produk.max' => 'Product Code cannot exceed 50 characters.',
            'nama_produk.max' => 'Product Name cannot exceed 255 characters.',
            'jumlah.required' => 'Quantity is required.',
            'jumlah.min' => 'Quantity cannot be less than 0.',
            'jumlah.numeric' => 'Quantity must be a number.',
            'alasan_retur.required' => 'Return Reason is required.',
            'alasan_retur.max' => 'Return Reason cannot exceed 255 characters.',
            'kode_satuan.max' => 'Unit Code cannot exceed 20 characters.',
        ];
    }
}