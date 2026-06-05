<?php

namespace App\Models;

use App\Filament\Resources\JournalEntries\JournalEntryResource;
use Illuminate\Database\Eloquent\Model;

class PostingQueue extends Model
{
    protected $table = 'posting_queue';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'source_id';

    protected static array $typeResourceMap = [
        'journal_entry' => JournalEntryResource::class,
        'cash_disbursement' => JournalEntryResource::class,
        'cash_receipt' => JournalEntryResource::class,
        'cash_transfer' => JournalEntryResource::class,
        'receivable_payment' => JournalEntryResource::class,
        'payable_payment' => JournalEntryResource::class,
        'sales_invoice' => JournalEntryResource::class,
        'sales_return' => JournalEntryResource::class,
        'goods_receipt' => JournalEntryResource::class,
        'purchase_invoice' => JournalEntryResource::class,
        'purchase_return' => JournalEntryResource::class,
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
        return JournalEntryResource::getUrl('edit', ['record' => $this->source_id]);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'journal_entry' => __('Journal Entry'),
            'cash_disbursement' => __('Cash Disbursement'),
            'cash_receipt' => __('Cash Receipt'),
            'cash_transfer' => __('Cash Transfer'),
            'receivable_payment' => __('Receivable Payment'),
            'payable_payment' => __('Payable Payment'),
            'sales_invoice' => __('Sales Invoice'),
            'sales_return' => __('Sales Return'),
            'goods_receipt' => __('Goods Receipt'),
            'purchase_invoice' => __('Purchase Invoice'),
            'purchase_return' => __('Purchase Return'),
            default => $this->type,
        };
    }
}
