<?php

namespace App\Support;

use App\Filament\Pages\Reports\GeneralLedger;
use App\Filament\Resources\JournalEntries\JournalEntryResource;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\PeriodClosing;
use Carbon\Carbon;

class ReportDrilldown
{
    /**
     * URL to General Ledger filtered by account and period.
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
     * Resolve edit/view URL for the journal entry's source document.
     */
    public static function sourceDocumentUrl(?JournalEntry $journalEntry, string $type = 'view'): string
    {
        if (! $journalEntry) {
            return '#';
        }

        $referenceType = $journalEntry->reference_type;
        $referenceId = $journalEntry->reference_id;

        if ($referenceType === PeriodClosing::class) {
            return \App\Filament\Pages\ManagePeriodClosings::getUrl([
                'year' => $journalEntry->date?->year,
            ]);
        }

        $resourceMap = [
            'App\Models\SalesInvoice' => \App\Filament\Resources\SalesInvoices\SalesInvoiceResource::class,
            'App\Models\PayablePayment' => \App\Filament\Resources\PayablePayments\PayablePaymentResource::class,
            'App\Models\ReceivablePayment' => \App\Filament\Resources\ReceivablePayments\ReceivablePaymentResource::class,
            'App\Models\CashReceipt' => \App\Filament\Resources\CashReceipts\CashReceiptResource::class,
            'App\Models\CashDisbursement' => \App\Filament\Resources\CashDisbursements\CashDisbursementResource::class,
            'App\Models\CashTransfer' => \App\Filament\Resources\CashTransfers\CashTransferResource::class,
            'App\Models\PurchaseInvoice' => \App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource::class,
            'App\Models\PurchaseOrder' => \App\Filament\Resources\PurchaseOrders\PurchaseOrderResource::class,
            'App\Models\SalesOrder' => \App\Filament\Resources\SalesOrders\SalesOrderResource::class,
            'App\Models\GoodsReceipt' => \App\Filament\Resources\GoodsReceipts\GoodsReceiptResource::class,
            // The Filament resource is named SalesDeliveryResource, but the underlying
            // model is DeliveryDocument (no App\Models\SalesDelivery exists).
            'App\Models\DeliveryDocument' => \App\Filament\Resources\SalesDeliveries\SalesDeliveryResource::class,
            'App\Models\PurchaseReturn' => \App\Filament\Resources\PurchaseReturns\PurchaseReturnResource::class,
            'App\Models\SalesReturn' => \App\Filament\Resources\SalesReturns\SalesReturnResource::class,
            'App\Models\AdvanceReceipt' => \App\Filament\Resources\AdvanceReceipts\AdvanceReceiptResource::class,
            'App\Models\AdvanceDisbursement' => \App\Filament\Resources\AdvanceDisbursements\AdvanceDisbursementResource::class,
            // FixedAssetTransaction.reference_id may not equal FixedAsset.id, so we map
            // it for convenience but the try/catch in getUrl will fall through to the
            // journal entry edit page if the resource rejects the id.
            'App\Models\FixedAssetTransaction' => \App\Filament\Resources\FixedAssets\FixedAssetResource::class,
        ];

        if ($referenceType && isset($resourceMap[$referenceType])) {
            $resource = $resourceMap[$referenceType];
            try {
                $page = ($type === 'view') ? 'view' : 'edit';

                return $resource::getUrl($page, ['record' => $referenceId]);
            } catch (\Exception $e) {
                try {
                    return $resource::getUrl('edit', ['record' => $referenceId]);
                } catch (\Exception $e2) {
                    // fall through to journal entry
                }
            }
        }

        return JournalEntryResource::getUrl('edit', ['record' => $journalEntry->id]);
    }

    public static function sourceDocumentUrlForItem(JournalEntryItem $item, string $type = 'view'): string
    {
        return self::sourceDocumentUrl($item->journalEntry, $type);
    }
}
