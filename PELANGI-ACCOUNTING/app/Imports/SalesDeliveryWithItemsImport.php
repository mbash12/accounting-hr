<?php

namespace App\Imports;

use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Project;
use App\Models\SalesOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesDeliveryWithItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        // Group rows by delivery number to process deliveries and their items together
        $deliveriesData = [];

        foreach ($rows as $row) {
            $deliveryNumber = (string) $row['nomor_pengiriman'];

            if (!isset($deliveriesData[$deliveryNumber])) {
                // Create the delivery data
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
                    throw new \Exception("Either Kode Customer or Nama Customer is required for delivery {$deliveryNumber}");
                }

                $salesOrderId = null;
                if (!empty($row['nomor_pesanan_penjualan'])) {
                    $salesOrder = SalesOrder::where('order_number', (string) $row['nomor_pesanan_penjualan'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$salesOrder) {
                        throw new \Exception("Sales Order with number '{$row['nomor_pesanan_penjualan']}' not found in current company for delivery {$deliveryNumber}");
                    }
                    $salesOrderId = $salesOrder->id;
                }

                $projectId = null;
                if (!empty($row['kode_proyek'])) {
                    $project = Project::where('project_code', (string) $row['kode_proyek'])
                        ->where('company_id', $companyId)
                        ->first();
                    if (!$project) {
                        throw new \Exception("Project with code '{$row['kode_proyek']}' not found in current company for delivery {$deliveryNumber}");
                    }
                    $projectId = $project->id;
                }

                $deliveriesData[$deliveryNumber] = [
                    'delivery_data' => [
                        'delivery_number' => $deliveryNumber,
                        'date' => isset($row['tanggal']) ? $this->parseDate($row['tanggal']) : now()->format('Y-m-d'),
                        'delivery_type' => isset($row['jenis']) ? (string) $row['jenis'] : 'goods',
                        'reference_no' => isset($row['nomor_referensi']) ? (string) $row['nomor_referensi'] : null,
                        'description' => isset($row['deskripsi']) ? (string) $row['deskripsi'] : null,
                        'status' => isset($row['status']) ? (string) $row['status'] : 'draft',
                        'customer_id' => $customerId,
                        'sales_order_id' => $salesOrderId,
                        'job_id' => $projectId,
                        'company_id' => $companyId,
                        'created_by_user_id' => Auth::id(),
                    ],
                    'items' => []
                ];
            }

            // Process the item for this delivery
            $productId = null;
            if (!empty($row['kode_produk'])) {
                $product = Product::where('code', (string) $row['kode_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with code '{$row['kode_produk']}' not found in current company for delivery {$deliveryNumber}");
                }
                $productId = $product->id;
            } elseif (!empty($row['nama_produk'])) {
                $product = Product::where('name', (string) $row['nama_produk'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$product) {
                    throw new \Exception("Product with name '{$row['nama_produk']}' not found in current company for delivery {$deliveryNumber}");
                }
                $productId = $product->id;
            } else {
                throw new \Exception("Either Kode Produk or Nama Produk is required for delivery {$deliveryNumber}");
            }

            $unitId = null;
            if (!empty($row['kode_satuan'])) {
                $unit = Unit::where('code', (string) $row['kode_satuan'])
                    ->where('company_id', $companyId)
                    ->first();
                if (!$unit) {
                    throw new \Exception("Unit with code '{$row['kode_satuan']}' not found in current company for delivery {$deliveryNumber}");
                }
                $unitId = $unit->id;
            }

            $deliveriesData[$deliveryNumber]['items'][] = [
                'description' => isset($row['deskripsi_item']) ? (string) $row['deskripsi_item'] : null,
                'quantity' => isset($row['jumlah']) ? (float) $row['jumlah'] : 0,
                'unit_price' => isset($row['harga_satuan']) ? (float) $row['harga_satuan'] : 0,
                'total' => isset($row['total_item']) ? (float) $row['total_item'] : 0,
                'product_id' => $productId,
                'unit_id' => $unitId,
                'created_by_user_id' => Auth::id(),
            ];
        }

        // Process each delivery and its items
        foreach ($deliveriesData as $deliveryNumber => $deliveryData) {
            $deliveryDocument = DeliveryDocument::where('delivery_number', $deliveryNumber)
                ->where('company_id', $companyId)
                ->first();

            if ($deliveryDocument) {
                // Update existing delivery - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = DeliveryDocument::getEventDispatcher();
                DeliveryDocument::unsetEventDispatcher();

                try {
                    $deliveryDocument->update($deliveryData['delivery_data']);
                    $deliveryDocument->items()->delete(); // Remove existing items to replace with new ones
                } finally {
                    // Re-enable model events
                    DeliveryDocument::setEventDispatcher($dispatcher);
                }
            } else {
                // Create new delivery - temporarily disable model events to avoid auto-generation conflicts
                $dispatcher = DeliveryDocument::getEventDispatcher();
                DeliveryDocument::unsetEventDispatcher();

                try {
                    $deliveryDocument = new DeliveryDocument();
                    $deliveryDocument->forceFill($deliveryData['delivery_data']);
                    $deliveryDocument->save();
                } finally {
                    // Re-enable model events
                    DeliveryDocument::setEventDispatcher($dispatcher);
                }
            }

            // Add items to the delivery
            foreach ($deliveryData['items'] as $itemData) {
                $itemData['delivery_document_id'] = $deliveryDocument->id;
                DeliveryDocumentItem::create($itemData);
            }
        }
    }

    /**
     * Prepare data for validation by converting all values to strings
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'nomor_pengiriman' => isset($data['nomor_pengiriman']) ? (string) $data['nomor_pengiriman'] : null,
            'tanggal' => isset($data['tanggal']) ? (string) $data['tanggal'] : null,
            'jenis' => isset($data['jenis']) ? (string) $data['jenis'] : null,
            'nomor_referensi' => isset($data['nomor_referensi']) ? (string) $data['nomor_referensi'] : null,
            'deskripsi' => isset($data['deskripsi']) ? (string) $data['deskripsi'] : null,
            'status' => isset($data['status']) ? (string) $data['status'] : null,
            'kode_customer' => isset($data['kode_customer']) ? (string) $data['kode_customer'] : null,
            'nama_customer' => isset($data['nama_customer']) ? (string) $data['nama_customer'] : null,
            'nomor_pesanan_penjualan' => isset($data['nomor_pesanan_penjualan']) ? (string) $data['nomor_pesanan_penjualan'] : null,
            'kode_proyek' => isset($data['kode_proyek']) ? (string) $data['kode_proyek'] : null,
            'kode_produk' => isset($data['kode_produk']) ? (string) $data['kode_produk'] : null,
            'nama_produk' => isset($data['nama_produk']) ? (string) $data['nama_produk'] : null,
            'deskripsi_item' => isset($data['deskripsi_item']) ? (string) $data['deskripsi_item'] : null,
            'jumlah' => isset($data['jumlah']) ? (string) $data['jumlah'] : null,
            'harga_satuan' => isset($data['harga_satuan']) ? (string) $data['harga_satuan'] : null,
            'total_item' => isset($data['total_item']) ? (string) $data['total_item'] : null,
            'kode_satuan' => isset($data['kode_satuan']) ? (string) $data['kode_satuan'] : null,
        ];
    }

    public function rules(): array
    {
        $selectedCompanyId = session('selected_company_id');
        $companyId = ($selectedCompanyId && $selectedCompanyId !== 'all') ? $selectedCompanyId : null;

        return [
            'nomor_pengiriman' => 'required|string|max:50',
            'tanggal' => 'required',
            'jenis' => 'nullable|in:goods,document|max:20',
            'nomor_referensi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,posted',
            'kode_customer' => 'nullable|string|max:50',
            'nama_customer' => 'nullable|string|max:255',
            'nomor_pesanan_penjualan' => 'nullable|string|max:100',
            'kode_proyek' => 'nullable|string|max:50',
            'kode_produk' => 'required_without:nama_produk|string|max:50',
            'nama_produk' => 'required_without:kode_produk|string|max:255',
            'deskripsi_item' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'total_item' => 'nullable|numeric|min:0',
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
            'nomor_pengiriman.required' => 'Nomor Pengiriman wajib diisi.',
            'nomor_pengiriman.max' => 'Nomor Pengiriman tidak boleh lebih dari 50 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'jenis.in' => 'Jenis harus goods atau document.',
            'nomor_referensi.max' => 'Nomor Referensi tidak boleh lebih dari 100 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'kode_customer.max' => 'Kode Customer tidak boleh lebih dari 50 karakter.',
            'nama_customer.max' => 'Nama Customer tidak boleh lebih dari 255 karakter.',
            'nomor_pesanan_penjualan.max' => 'Nomor Pesanan Penjualan tidak boleh lebih dari 100 karakter.',
            'kode_proyek.max' => 'Kode Proyek tidak boleh lebih dari 50 karakter.',
            'kode_produk.max' => 'Kode Produk tidak boleh lebih dari 50 karakter.',
            'nama_produk.max' => 'Nama Produk tidak boleh lebih dari 255 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'harga_satuan.min' => 'Harga Satuan tidak boleh kurang dari 0.',
            'harga_satuan.numeric' => 'Harga Satuan harus berupa angka.',
            'total_item.min' => 'Total Item tidak boleh kurang dari 0.',
            'total_item.numeric' => 'Total Item harus berupa angka.',
            'kode_satuan.max' => 'Kode Satuan tidak boleh lebih dari 20 karakter.',
        ];
    }
}