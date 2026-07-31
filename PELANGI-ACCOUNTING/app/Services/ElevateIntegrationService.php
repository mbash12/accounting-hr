<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Department;
use App\Models\DeliveryDocument;
use App\Models\DeliveryDocumentItem;
use App\Models\ElevateWorkOrderMapping;
use App\Models\JournalEntry;
use App\Models\Product;
use App\Models\ReceivablePayment;
use App\Models\ReceivablePaymentItem;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ElevateIntegrationService
{
    public function __construct(
        protected CodeGeneratorService    $codeGenerator,
        protected ReceivablePayableService $receivablePayableService,
        protected JournalService          $journalService,
    ) {}

   
    public function processWorkOrder(array $payload): array
    {
        $workOrderId     = (string) $payload['work_order_id'];
        $workOrderNumber = $payload['work_order_number'] ?? $workOrderId;
        $companyId       = (int) $payload['company_id'];

        Log::info('[Elevate] Processing Work Order', [
            'work_order_id'     => $workOrderId,
            'work_order_number' => $workOrderNumber,
            'company_id'        => $companyId,
        ]);

        $mapping = ElevateWorkOrderMapping::where('work_order_id', $workOrderId)->first();

        if ($mapping && $mapping->isCompleted()) {
            Log::info('[Elevate] Work Order already completed — returning existing IDs', [
                'work_order_id' => $workOrderId,
            ]);
            return $this->buildResult($mapping, 'already_processed');
        }

        if (!$mapping) {
            $mapping = ElevateWorkOrderMapping::create([
                'work_order_id'     => $workOrderId,
                'work_order_number' => $workOrderNumber,
                'company_id'        => $companyId,
                'status'            => ElevateWorkOrderMapping::STATUS_PENDING,
                'payload'           => $payload,
            ]);
        }

        try {
            DB::transaction(function () use ($mapping, $payload, $workOrderId, $workOrderNumber, $companyId) {

                if (!$mapping->contact_id) {
                    $contact = $this->findOrCreateContact($payload['customer'], $companyId);
                    $mapping->update([
                        'contact_id' => $contact->id,
                        'status'     => ElevateWorkOrderMapping::STATUS_CONTACT_RESOLVED,
                    ]);
                    Log::info('[Elevate] Contact resolved', [
                        'work_order_id' => $workOrderId,
                        'contact_id'    => $contact->id,
                        'is_new'        => $contact->wasRecentlyCreated,
                    ]);
                }

                if (!$mapping->sales_invoice_id) {
                    $invoice = $this->createSalesInvoice(
                        $mapping->contact_id,
                        $workOrderNumber,
                        $payload['items'] ?? [],
                        $companyId,
                        $payload['invoice_date'] ?? now()->toDateString(),
                        isset($payload['billing_amount']) ? (float) $payload['billing_amount'] : null,
                        $payload['description'] ?? null
                    );

                    $mapping->update([
                        'sales_invoice_id' => $invoice->id,
                        'status'           => ElevateWorkOrderMapping::STATUS_INVOICE_CREATED,
                    ]);

                    Log::info('[Elevate] Sales Invoice created (draft)', [
                        'work_order_id'  => $workOrderId,
                        'invoice_id'     => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'total_amount'   => $invoice->total_amount,
                    ]);
                }

                $invoice = SalesInvoice::findOrFail($mapping->sales_invoice_id);
                $invoice->createJournalEntry();
                $this->postSalesInvoiceJournal($invoice);

                // Check materials for Sales Delivery creation
                $materials = [];
                if (!empty($payload['materials']) && is_array($payload['materials'])) {
                    $materials = $payload['materials'];
                } elseif (!empty($payload['items']) && is_array($payload['items'])) {
                    foreach ($payload['items'] as $item) {
                        $code = strtoupper(trim($item['product_code'] ?? ''));
                        $isMatFlag = !empty($item['is_material']) || ($item['type'] ?? '') === 'material';
                        $isMatCode = str_starts_with($code, 'MAT');

                        $product = null;
                        if (!empty($item['product_code'])) {
                            $product = Product::where('code', $item['product_code'])
                                ->where(function ($q) use ($companyId) {
                                    $q->where('company_id', $companyId)->orWhereNull('company_id');
                                })
                                ->first();
                        }

                        $isGoodType = $product && ($product->product_type === 'good' || $product->product_type !== 'service');

                        if ($isMatFlag || $isMatCode || $isGoodType) {
                            $materials[] = $item;
                        }
                    }
                }

                if (!empty($materials)) {
                    if (!$mapping->delivery_document_id) {
                        $delivery = $this->createSalesDelivery(
                            $mapping->contact_id,
                            $workOrderNumber,
                            $materials,
                            $companyId,
                            $payload['delivery_date'] ?? $payload['invoice_date'] ?? now()->toDateString(),
                            $payload['description'] ?? null
                        );

                        $mapping->update([
                            'delivery_document_id' => $delivery->id,
                            'status'               => ElevateWorkOrderMapping::STATUS_DELIVERY_CREATED,
                        ]);

                        Log::info('[Elevate] Sales Delivery created (draft)', [
                            'work_order_id'   => $workOrderId,
                            'delivery_id'     => $delivery->id,
                            'delivery_number' => $delivery->delivery_number,
                        ]);
                    }

                    $delivery = DeliveryDocument::findOrFail($mapping->delivery_document_id);
                    $this->postSalesDeliveryJournal($delivery);
                }

                if (!$mapping->receivable_payment_id) {
                    $bankAccountId = 3539; //Tri Harmoni No. Rek  377988787-8 BNI

                    $payment = $this->createReceivablePayment(
                        $invoice,
                        $mapping->contact_id,
                        $workOrderNumber,
                        $bankAccountId,
                        $companyId,
                        $payload['payment_date'] ?? now()->toDateString()
                    );

                    $mapping->update([
                        'receivable_payment_id' => $payment->id,
                        'status'                => ElevateWorkOrderMapping::STATUS_PAYMENT_CREATED,
                    ]);

                    Log::info('[Elevate] Receivable Payment created (draft journal)', [
                        'work_order_id'  => $workOrderId,
                        'payment_id'     => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'total_payment'  => $payment->total_payment,
                    ]);
                }

                $payment = ReceivablePayment::findOrFail($mapping->receivable_payment_id);
                $this->postReceivablePaymentJournal($payment);

                $invoice->refresh();
                $this->receivablePayableService->updateOutstandingJournalEntryForSalesInvoice($invoice);

                $mapping->update([
                    'status'        => ElevateWorkOrderMapping::STATUS_COMPLETED,
                    'error_message' => null,
                ]);
            });

            $mapping->refresh();

            Log::info('[Elevate] Work Order processing completed successfully', [
                'work_order_id'         => $workOrderId,
                'contact_id'            => $mapping->contact_id,
                'sales_invoice_id'      => $mapping->sales_invoice_id,
                'receivable_payment_id' => $mapping->receivable_payment_id,
            ]);

            return $this->buildResult($mapping, 'created');

        } catch (\Throwable $e) {
            $mapping->markFailed($e->getMessage());

            Log::error('[Elevate] Work Order processing failed', [
                'work_order_id' => $workOrderId,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    protected function postSalesInvoiceJournal(SalesInvoice $invoice): void
    {
        $journalEntry = JournalEntry::where('reference_type', SalesInvoice::class)
            ->where('reference_id', $invoice->id)
            ->where('sub_module', 'sales_invoice')
            ->first();

        if (!$journalEntry) {
            Log::warning('[Elevate] No draft journal entry found for Sales Invoice — skipping posting', [
                'invoice_id' => $invoice->id,
            ]);
        } else {
            $journalEntry->update([
                'is_posted'          => true,
                'status'             => 'posted',
                'posted_by_user_id'  => $this->getSystemUserId(),
                'posted_at'          => now(),
                'updated_by_user_id' => $this->getSystemUserId(),
            ]);
        }

        $invoice->update([
            'status'             => 'posted',
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);
    }

    protected function postSalesDeliveryJournal(DeliveryDocument $delivery): void
    {
        $journalEntry = JournalEntry::where('reference_type', DeliveryDocument::class)
            ->where('reference_id', $delivery->id)
            ->where('sub_module', 'delivery_document')
            ->first();

        if (!$journalEntry) {
            $delivery->createJournalEntry();
            $journalEntry = JournalEntry::where('reference_type', DeliveryDocument::class)
                ->where('reference_id', $delivery->id)
                ->where('sub_module', 'delivery_document')
                ->first();
        }

        if (!$journalEntry) {
            Log::warning('[Elevate] No draft journal entry found for Sales Delivery — skipping posting', [
                'delivery_id' => $delivery->id,
            ]);
        } else {
            $journalEntry->update([
                'is_posted'          => true,
                'status'             => 'posted',
                'posted_by_user_id'  => $this->getSystemUserId(),
                'posted_at'          => now(),
                'updated_by_user_id' => $this->getSystemUserId(),
            ]);
        }

        $delivery->update([
            'status'             => 'delivered',
            'is_locked'          => true,
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);
    }

    
    protected function postReceivablePaymentJournal(ReceivablePayment $payment): void
    {
        $journalEntry = JournalEntry::where('reference_type', ReceivablePayment::class)
            ->where('reference_id', $payment->id)
            ->where('sub_module', 'receivable_payment')
            ->first();

        if (!$journalEntry) {
            Log::warning('[Elevate] No draft journal entry found for Receivable Payment — skipping posting', [
                'payment_id' => $payment->id,
            ]);
        } else {
            $journalEntry->update([
                'is_posted'          => true,
                'status'             => 'posted',
                'posted_by_user_id'  => $this->getSystemUserId(),
                'posted_at'          => now(),
                'updated_by_user_id' => $this->getSystemUserId(),
            ]);
        }

        $payment->update([
            'status'             => 'completed',
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);
    }

   
    protected function findOrCreateContact(array $customerData, int $companyId): Contact
    {
        $email = trim($customerData['email'] ?? '');
        $name  = trim($customerData['name']  ?? '');

        if (empty($email)) {
            throw new \InvalidArgumentException('Customer email is required to find or create a contact.');
        }

        $contact = Contact::where('email', $email)
            ->where('is_customer', true)
            ->where('company_id', $companyId)
            ->first();

        if ($contact) {
            return $contact;
        }

        return Contact::create([
            'name'               => $name ?: $email,
            'email'              => $email,
            'phone'              => $customerData['phone'] ?? null,
            'is_customer'        => true,
            'is_supplier'        => false,
            'is_employee'        => false,
            'is_active'          => true,
            'company_id'         => $companyId,
            'created_by_user_id' => $this->getSystemUserId(),
        ]);
    }


    protected function createSalesInvoice(
        int    $contactId,
        string $workOrderNumber,
        array  $items,
        int    $companyId,
        string $invoiceDate,
        ?float $billingAmount = null,
        ?string $woDescription = null
    ): SalesInvoice {
        $existing = SalesInvoice::where('reference_no', $workOrderNumber)
            ->where('company_id', $companyId)
            ->first();
        if ($existing) {
            return $existing;
        }


        $totals = $this->calculateInvoiceTotals($items);

        $invoice = SalesInvoice::create([
            'invoice_number'     => null,          
            'date'               => $invoiceDate,
            'due_date'           => $invoiceDate,  
            'reference_no'       => $workOrderNumber,
            'description'        => 'Work Order: ' . $workOrderNumber,
            'customer_id'        => $contactId,
            'company_id'         => $companyId,
            'subtotal'           => $totals['subtotal'],
            'discount'           => 0,
            'tax_amount'         => $totals['tax_amount'],
            'other_charges'      => 0,
            'total_amount'       => $totals['total_amount'],
            'paid_amount'        => 0,
            'outstanding_amount' => $totals['total_amount'],
            'is_paid'            => false,
            'status'             => 'draft',
            'is_locked'          => false,
            'created_by_user_id' => $this->getSystemUserId(),
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);

        foreach ($items as $item) {
            $product   = $this->resolveProduct($item['product_code'] ?? null, 'Jasa '.$item['description'] ?? 'Item', $companyId);
            $unit      = $this->resolveUnit($item['unit_code'] ?? null, $companyId);
            $qty       = (float) ($item['quantity']   ?? 1);
            $price     = (float) ($item['unit_price'] ?? 0);
            $lineTotal = $qty * $price;

            if (!$product) {
                throw new \InvalidArgumentException(
                    "Kode produk tidak valid pada item."
                );
            }

            if (!$unit) {
                $unit = Unit::where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId)->orWhereNull('company_id');
                })->first();

                if (!$unit) {
                    throw new \InvalidArgumentException(
                        "Unit dengan kode '{$item['unit_code']}' tidak ditemukan dan tidak ada unit default."
                    );
                }
            }

            SalesInvoiceItem::create([
                'sales_invoice_id'    => $invoice->id,
                'product_id'          => $product->id,
                'unit_id'             => $unit->id,
                'description'         => $item['description'] ?? $product->name ?? 'Item',
                'quantity'            => $qty,
                'unit_price'          => $price,
                'total'               => $lineTotal,
                'tax_id'              => null,
                'tax_amount'          => 0,
                'discount'            => 0,
                'discount_percentage' => 0,
            ]);
        }

        return $invoice;
    }

    protected function createReceivablePayment(
        SalesInvoice $invoice,
        int          $contactId,
        string       $workOrderNumber,
        int          $bankAccountId,
        int          $companyId,
        string       $paymentDate
    ): ReceivablePayment {
        $existing = ReceivablePayment::where('reference_no', $workOrderNumber)
            ->where('company_id', $companyId)
            ->first();
        if ($existing) {
            return $existing;
        }

        $totalAmount = (float) $invoice->total_amount;

        $payment = ReceivablePayment::create([
            'payment_number'     => null,          
            'payment_date'       => $paymentDate,
            'reference_no'       => $workOrderNumber,
            'description'        => 'Payment for Work Order: ' . $workOrderNumber,
            'customer_id'        => $contactId,
            'bank_account_id'    => $bankAccountId,
            'total_payment'      => $totalAmount,
            'payment_method'     => 'bank_transfer',
            'status'             => 'draft',      
            'company_id'         => $companyId,
            'created_by_user_id' => $this->getSystemUserId(),
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);

        ReceivablePaymentItem::create([
            'receivable_payment_id' => $payment->id,
            'sales_invoice_id'      => $invoice->id,
            'date'                  => $paymentDate,
            'amount'                => $totalAmount,
            'paid_amount'           => 0,
            'discount_amount'       => 0,
            'write_off_amount'      => 0,
            'set_payment'           => $totalAmount,
        ]);

        $invoice->update([
            'paid_amount'        => $totalAmount,
            'outstanding_amount' => 0,
            'is_paid'            => true,
            'status'             => 'paid',
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);

        $payment->refresh();
        $this->receivablePayableService->createJournalEntryForReceivablePayment($payment);

        return $payment;
    }
    protected function calculateInvoiceTotals(array $items): array
    {
        $subtotal  = 0.0;
        $taxAmount = 0.0;

        foreach ($items as $item) {
            $qty   = (float) ($item['quantity']   ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $subtotal += $qty * $price;
        }

        return [
            'subtotal'     => round($subtotal, 2),
            'tax_amount'   => round($taxAmount, 2),
            'total_amount' => round($subtotal + $taxAmount, 2),
        ];
    }

    protected function resolveProduct(?string $productCode, string $productName, int $companyId): ?Product
    {
        if (!$productCode) {
            return null;
        }

        $product = Product::where('code', $productCode)
            ->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            })
            ->first();

        if (!$product) {
            Log::info('[Elevate] Product not found by code, creating new one', [
                'product_code' => $productCode,
                'product_name' => $productName,
                'company_id'   => $companyId,
            ]);

            $productType = str_starts_with(strtoupper($productCode), 'MAT') ? 'good' : 'service';

            $product = Product::create([
                'code'               => $productCode,
                'name'               => $productName,
                'product_type'       => $productType,
                'is_active'          => true,
                'company_id'         => $companyId,
                'unit_id'            => 26,
                'created_by_user_id' => $this->getSystemUserId(),
                'updated_by_user_id' => $this->getSystemUserId(),
            ]);
        }

        return $product;
    }

    protected function resolveUnit(?string $unitCode, int $companyId): ?Unit
    {
        if (!$unitCode) {
            return null;
        }

        return Unit::where('code', $unitCode)
            ->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            })
            ->first();
    }

    protected function resolveBankAccountId(?int $bankAccountId, int $companyId): int
    {
        if ($bankAccountId) {
            $account = Account::where('id', $bankAccountId)
                ->where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId)->orWhereNull('company_id');
                })
                ->where('is_cash_bank', true)
                ->where('is_active', true)
                ->where('is_header', false)
                ->first();

            if ($account) {
                return $account->id;
            }
        }

        $fallback = Account::where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            })
            ->where('is_cash_bank', true)
            ->where('is_active', true)
            ->where('is_header', false)
            ->orderBy('code')
            ->first();

        if (!$fallback) {
            throw new \InvalidArgumentException(
                "No active Cash/Bank COA account found for company_id={$companyId}. " .
                "Please create a Cash/Bank account or provide a valid 'bank_account_id' in the payload."
            );
        }

        return $fallback->id;
    }

    protected function createSalesDelivery(
        int    $contactId,
        string $workOrderNumber,
        array  $materials,
        int    $companyId,
        string $deliveryDate,
        ?string $woDescription = null
    ): DeliveryDocument {
        $existing = DeliveryDocument::where('reference_no', $workOrderNumber)
            ->where('company_id', $companyId)
            ->first();
        if ($existing) {
            return $existing;
        }

        $delivery = DeliveryDocument::create([
            'delivery_number'    => null,
            'date'               => $deliveryDate,
            'reference_no'       => $workOrderNumber,
            'description'        => 'Sales Delivery for Work Order: ' . $workOrderNumber,
            'customer_id'        => $contactId,
            'company_id'         => $companyId,
            'status'             => 'draft',
            'is_locked'          => false,
            'created_by_user_id' => $this->getSystemUserId(),
            'updated_by_user_id' => $this->getSystemUserId(),
        ]);

        foreach ($materials as $mat) {
            $productCode = $mat['product_code'] ?? null;
            $description = $mat['description'] ?? 'Material ' . ($productCode ?? 'Item');
            
            $product = $this->resolveProduct($productCode, $description, $companyId);

            $costPrice = isset($mat['cost_price']) ? (float) $mat['cost_price'] : (isset($mat['unit_price']) ? (float) $mat['unit_price'] : 0);
            if ($product && $costPrice > 0 && ($product->cost_price <= 0 || isset($mat['cost_price']))) {
                $product->update(['cost_price' => $costPrice]);
            }

            $unit = $this->resolveUnit($mat['unit_code'] ?? null, $companyId);
            if (!$unit) {
                $unit = Unit::where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId)->orWhereNull('company_id');
                })->first();
            }

            $qty = (float) ($mat['quantity'] ?? 1);
            $warehouseId = $mat['warehouse_id'] ?? null;

            DeliveryDocumentItem::create([
                'delivery_document_id' => $delivery->id,
                'product_id'           => $product?->id,
                'unit_id'              => $unit?->id,
                'description'          => $description,
                'quantity'             => $qty,
                'total_quantity'       => (string) $qty,
                'warehouse_id'         => $warehouseId,
            ]);
        }

        $delivery->createJournalEntry();

        return $delivery;
    }

    protected function getSystemUserId(): int
    {
        return (int) config('elevate.system_user_id', 1);
    }

    protected function buildResult(ElevateWorkOrderMapping $mapping, string $action): array
    {
        $invoice = $mapping->sales_invoice_id
            ? SalesInvoice::find($mapping->sales_invoice_id)
            : null;

        $delivery = $mapping->delivery_document_id
            ? DeliveryDocument::find($mapping->delivery_document_id)
            : null;

        $payment = $mapping->receivable_payment_id
            ? ReceivablePayment::find($mapping->receivable_payment_id)
            : null;

        return [
            'action'                 => $action,
            'work_order_id'          => $mapping->work_order_id,
            'work_order_number'      => $mapping->work_order_number,
            'contact_id'             => $mapping->contact_id,
            'sales_invoice_id'       => $mapping->sales_invoice_id,
            'sales_invoice_number'   => $invoice?->invoice_number,
            'delivery_document_id'   => $mapping->delivery_document_id,
            'delivery_number'        => $delivery?->delivery_number,
            'receivable_payment_id'  => $mapping->receivable_payment_id,
            'payment_number'         => $payment?->payment_number,
            'total_amount'           => $invoice?->total_amount,
            'status'                 => $mapping->status,
        ];
    }
}
