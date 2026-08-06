<?php

namespace App\Support;

use App\Filament\Pages\ManageOpeningBalances;
use App\Filament\Pages\ManagePeriodClosings;
use App\Filament\Pages\Reports\GeneralLedger;
use App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource;
use App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource;
use App\Filament\Resources\CashDisbursements\CashDisbursementResource;
use App\Filament\Resources\CashReceipts\CashReceiptResource;
use App\Filament\Resources\CashTransfers\CashTransferResource;
use App\Filament\Resources\FixedAssets\FixedAssetResource;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use App\Filament\Resources\JournalEntries\JournalEntryResource;
use App\Filament\Resources\PayablePayments\PayablePaymentResource;
use App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\PurchaseReturns\PurchaseReturnResource;
use App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource;
use App\Filament\Resources\SalesDeliveries\SalesDeliveryResource;
use App\Filament\Resources\SalesInvoices\SalesInvoiceResource;
use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Filament\Resources\SalesReturns\SalesReturnResource;
use App\Models\AdvanceDisbursement;
use App\Models\AdvanceReceipt;
use App\Models\CashDisbursement;
use App\Models\CashReceipt;
use App\Models\CashTransfer;
use App\Models\DeliveryDocument;
use App\Models\FixedAssetTransaction;
use App\Models\GoodsReceipt;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\OpeningBalance;
use App\Models\PayablePayment;
use App\Models\PeriodClosing;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\ReceivablePayment;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\SalesReturn;
use Carbon\Carbon;
use Filament\Resources\Resource;

class ReportDrilldown
{
    /**
     * URL to Laporan Buku Besar filtered by account and period.
     */
    public static function generalLedgerUrl(int|string $accountId, string $startDate, string $endDate): string
    {
        $query = http_build_query([
            'account_ids' => [(int) $accountId],
            'start_date' => Carbon::parse($startDate)->format('Y-m-d'),
            'end_date' => Carbon::parse($endDate)->format('Y-m-d'),
        ]);

        return GeneralLedger::getUrl().'?'.$query;
    }

    /**
     * As-of report (Neraca / Saldo Akun): YTD window ending on the as-of date.
     */
    public static function generalLedgerUrlAsOf(int|string $accountId, string $asOfDate): string
    {
        $end = Carbon::parse($asOfDate);

        return self::generalLedgerUrl(
            $accountId,
            $end->copy()->startOfYear()->format('Y-m-d'),
            $end->format('Y-m-d'),
        );
    }

    /**
     * Opening-balance detail ending immediately before the report period.
     */
    public static function generalLedgerUrlBefore(int|string $accountId, string $startDate): string
    {
        $end = Carbon::parse($startDate)->subDay();

        return self::generalLedgerUrl(
            $accountId,
            $end->copy()->startOfYear()->format('Y-m-d'),
            $end->format('Y-m-d'),
        );
    }

    /**
     * URL to edit/view a fixed asset master record.
     */
    public static function fixedAssetUrl(int|string $fixedAssetId): string
    {
        return FixedAssetResource::getUrl('edit', ['record' => $fixedAssetId]);
    }

    /**
     * Resolve URL for the original source document behind a journal entry.
     */
    public static function sourceDocumentUrl(?JournalEntry $journalEntry, string $type = 'view'): string
    {
        if (! $journalEntry) {
            return '#';
        }

        if (
            $journalEntry->reference_type === PeriodClosing::class
            || $journalEntry->sub_module === 'period_closing'
        ) {
            return ManagePeriodClosings::getUrl([
                'year' => $journalEntry->date?->year,
            ]);
        }

        if (
            $journalEntry->reference_type === OpeningBalance::class
            || $journalEntry->sub_module === 'opening_balance'
        ) {
            return ManageOpeningBalances::getUrl();
        }

        // Manual general journal (no source document).
        if (! $journalEntry->reference_type || ! $journalEntry->reference_id) {
            return JournalEntryResource::getUrl('edit', ['record' => $journalEntry->id]);
        }

        $referenceType = self::normalizeReferenceType($journalEntry->reference_type);

        if ($referenceType === FixedAssetTransaction::class) {
            $transaction = $journalEntry->relationLoaded('reference')
                ? $journalEntry->reference
                : FixedAssetTransaction::find($journalEntry->reference_id);

            if ($transaction?->fixed_asset_id) {
                return FixedAssetResource::getUrl('edit', ['record' => $transaction->fixed_asset_id]);
            }

            return '#';
        }

        /** @var class-string<Resource>|null $resource */
        $resource = self::resourceForReference($referenceType);
        if ($resource) {
            $pages = $resource::getPages();
            $page = match (true) {
                $type === 'view' && isset($pages['view']) => 'view',
                isset($pages['edit']) => 'edit',
                isset($pages['view']) => 'view',
                default => null,
            };

            if ($page) {
                try {
                    return $resource::getUrl($page, ['record' => $journalEntry->reference_id]);
                } catch (\Throwable) {
                    // Fall through to journal entry.
                }
            }
        }

        // Unknown / unmapped reference types keep a usable fallback.
        return JournalEntryResource::getUrl('edit', ['record' => $journalEntry->id]);
    }

    public static function sourceDocumentUrlForItem(JournalEntryItem $item, string $type = 'view'): string
    {
        return self::sourceDocumentUrl($item->journalEntry, $type);
    }

    /**
     * Resolve the order related to a sales or purchase invoice journal.
     */
    public static function relatedOrderUrl(?JournalEntry $journalEntry): string
    {
        if (! $journalEntry?->reference_id) {
            return '#';
        }

        $referenceType = self::normalizeReferenceType($journalEntry->reference_type);

        try {
            if ($referenceType === SalesInvoice::class) {
                $invoice = $journalEntry->relationLoaded('reference')
                    ? $journalEntry->reference
                    : SalesInvoice::find($journalEntry->reference_id);

                return $invoice?->sales_order_id
                    ? SalesOrderResource::getUrl('edit', ['record' => $invoice->sales_order_id])
                    : '#';
            }

            if ($referenceType === PurchaseInvoice::class) {
                $invoice = $journalEntry->relationLoaded('reference')
                    ? $journalEntry->reference
                    : PurchaseInvoice::find($journalEntry->reference_id);

                return $invoice?->purchase_order_id
                    ? PurchaseOrderResource::getUrl('edit', ['record' => $invoice->purchase_order_id])
                    : '#';
            }
        } catch (\Throwable) {
            return '#';
        }

        return '#';
    }

    /**
     * Human label for the related order link in journal voucher UI.
     */
    public static function relatedOrderLabel(?JournalEntry $journalEntry): ?string
    {
        return match (self::normalizeReferenceType($journalEntry?->reference_type)) {
            SalesInvoice::class => __('Pesanan Penjualan (SO)'),
            PurchaseInvoice::class => __('Pesanan Pembelian (PO)'),
            default => null,
        };
    }

    /**
     * Human label for the source document link in journal voucher UI.
     */
    public static function sourceDocumentLabel(?JournalEntry $journalEntry): ?string
    {
        if (! $journalEntry) {
            return null;
        }

        if (
            $journalEntry->reference_type === PeriodClosing::class
            || $journalEntry->sub_module === 'period_closing'
        ) {
            return __('Tutup Buku');
        }

        if (
            $journalEntry->reference_type === OpeningBalance::class
            || $journalEntry->sub_module === 'opening_balance'
        ) {
            return __('Saldo Awal');
        }

        if (! $journalEntry->reference_type || ! $journalEntry->reference_id) {
            return __('Jurnal Umum');
        }

        $referenceType = self::normalizeReferenceType($journalEntry->reference_type);

        return match ($referenceType) {
            SalesOrder::class => __('Pesanan Penjualan'),
            DeliveryDocument::class => __('Pengiriman Penjualan'),
            SalesInvoice::class => __('Faktur Penjualan'),
            SalesReturn::class => __('Retur Penjualan'),
            PurchaseOrder::class => __('Pesanan Pembelian'),
            GoodsReceipt::class => __('Penerimaan Barang'),
            PurchaseInvoice::class => __('Faktur Pembelian'),
            PurchaseReturn::class => __('Retur Pembelian'),
            ReceivablePayment::class => __('Penerimaan Piutang Usaha'),
            PayablePayment::class => __('Pembayaran Hutang Usaha'),
            CashReceipt::class => __('Penerimaan Kas'),
            CashDisbursement::class => __('Pengeluaran Kas'),
            CashTransfer::class => __('Transfer Kas'),
            AdvanceReceipt::class => __('Penerimaan Uang Muka'),
            AdvanceDisbursement::class => __('Pengeluaran Uang Muka'),
            FixedAssetTransaction::class => __('Harta Tetap'),
            default => __('Dokumen Sumber'),
        };
    }

    /**
     * @return class-string<Resource>|null
     */
    protected static function resourceForReference(?string $referenceType): ?string
    {
        $referenceType = self::normalizeReferenceType($referenceType);

        return match ($referenceType) {
            SalesOrder::class => SalesOrderResource::class,
            DeliveryDocument::class => SalesDeliveryResource::class,
            SalesInvoice::class => SalesInvoiceResource::class,
            SalesReturn::class => SalesReturnResource::class,
            PurchaseOrder::class => PurchaseOrderResource::class,
            GoodsReceipt::class => GoodsReceiptResource::class,
            PurchaseInvoice::class => PurchaseInvoiceResource::class,
            PurchaseReturn::class => PurchaseReturnResource::class,
            ReceivablePayment::class => ReceivablePaymentResource::class,
            PayablePayment::class => PayablePaymentResource::class,
            CashReceipt::class => CashReceiptResource::class,
            CashDisbursement::class => CashDisbursementResource::class,
            CashTransfer::class => CashTransferResource::class,
            AdvanceReceipt::class => AdvanceReceiptResource::class,
            AdvanceDisbursement::class => AdvanceDisbursementResource::class,
            default => null,
        };
    }

    protected static function normalizeReferenceType(?string $referenceType): ?string
    {
        if (! $referenceType) {
            return null;
        }

        // Legacy outstanding journals stored App\Services\* instead of App\Models\*.
        if (str_starts_with($referenceType, 'App\\Services\\')) {
            return 'App\\Models\\'.class_basename($referenceType);
        }

        return $referenceType;
    }
}
