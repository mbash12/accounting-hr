<?php

namespace App\Models;

use App\Filament\Resources\CashDisbursements\CashDisbursementResource;
use App\Filament\Resources\CashReceipts\CashReceiptResource;
use App\Filament\Resources\CashTransfers\CashTransferResource;
use App\Filament\Resources\GoodsReceipts\GoodsReceiptResource;
use App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource;
use App\Filament\Resources\PurchaseReturns\PurchaseReturnResource;
use App\Filament\Resources\SalesInvoices\SalesInvoiceResource;
use App\Filament\Resources\SalesReturns\SalesReturnResource;
use Illuminate\Database\Eloquent\Model;

class PostingQueue extends Model
{
    protected $table = 'posting_queue';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'source_id';

    protected static array $typeResourceMap = [
        'cash_disbursement' => CashDisbursementResource::class,
        'cash_receipt' => CashReceiptResource::class,
        'cash_transfer' => CashTransferResource::class,
        'sales_invoice' => SalesInvoiceResource::class,
        'sales_return' => SalesReturnResource::class,
        'goods_receipt' => GoodsReceiptResource::class,
        'purchase_invoice' => PurchaseInvoiceResource::class,
        'purchase_return' => PurchaseReturnResource::class,
    ];

    // Composite key: model doesn't have a real PK — prevent default sort on it
    public function getKeyName()
    {
        return 'source_id';
    }

    public function getQualifiedKeyName()
    {
        return $this->getTable() . '.source_id';
    }

    protected $fillable = [];

    // Read-only view — prevent any write operations
    public function save(array $options = [])
    {
        throw new \RuntimeException('Cannot write to a database view.');
    }

    public static function create(array $attributes = [])
    {
        throw new \RuntimeException('Cannot write to a database view.');
    }

    public function delete()
    {
        throw new \RuntimeException('Cannot delete from a database view.');
    }

    public function getSourceModel(): ?Model
    {
        if (!$this->source_type || !$this->source_id) {
            return null;
        }

        // PostgreSQL view may store escaped backslashes (App\\Models\\Foo)
        $class = str_replace('\\\\', '\\', $this->source_type);
        if (!class_exists($class)) {
            return null;
        }

        return $class::find($this->source_id);
    }

    public function getResourceUrl(): ?string
    {
        $resourceClass = static::$typeResourceMap[$this->type] ?? null;

        if (!$resourceClass) {
            return null;
        }

        return $resourceClass::getUrl('edit', ['record' => $this->source_id]);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'cash_disbursement' => __('Cash Disbursement'),
            'cash_receipt' => __('Cash Receipt'),
            'cash_transfer' => __('Cash Transfer'),
            'sales_invoice' => __('Sales Invoice'),
            'sales_return' => __('Sales Return'),
            'goods_receipt' => __('Goods Receipt'),
            'purchase_invoice' => __('Purchase Invoice'),
            'purchase_return' => __('Purchase Return'),
            default => $this->type,
        };
    }
}
