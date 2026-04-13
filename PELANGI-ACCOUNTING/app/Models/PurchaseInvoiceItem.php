<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->purchaseInvoice && $model->purchaseInvoice->is_locked) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => 'Tidak dapat menambah item baru pada invoice yang terkunci.',
                ]);
            }
        });

        static::updating(function ($model) {
            if ($model->purchaseInvoice?->getOriginal('is_locked')) {
                $model->purchase_order_item_id = $model->getOriginal('purchase_order_item_id');
                $model->product_id = $model->getOriginal('product_id');
                $model->unit_id = $model->getOriginal('unit_id');
                $model->description = $model->getOriginal('description');

                $originalQty = (float) ($model->getOriginal('quantity') ?? 0);
                $newQty = (float) ($model->quantity ?? 0);
                if ($newQty > $originalQty) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => 'Kuantitas tidak boleh ditambah pada invoice yang terkunci.',
                    ]);
                }
            }
        });

        static::saving(function ($model) {
            $qty = is_numeric($model->quantity) ? (float) $model->quantity : 0;
            $price = is_numeric($model->unit_price) ? (float) $model->unit_price : 0;
            $model->total = $qty * $price;

            // Validate against order quantity
            if ($model->purchase_order_item_id) {
                $orderItem = \App\Models\PurchaseOrderItem::find($model->purchase_order_item_id);
                if ($orderItem) {
                    $totalInvoiced = self::where('purchase_order_item_id', $model->purchase_order_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('purchaseInvoice', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalInvoiced + $model->quantity) > $orderItem->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'quantity' => 'Total kuantitas invoice tidak boleh melebihi kuantitas pesanan.',
                        ]);
                    }
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'unit_price',
        'total',
        'description',
        'purchase_invoice_id',
        'purchase_order_item_id',
        'product_id',
        'item_name',
        'unit_id',
        'tax_id',
        'cost_center_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
            'purchase_invoice_id' => 'integer',
            'purchase_order_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'tax_id' => 'integer',
            'cost_center_id' => 'integer',
        ];
    }

    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}
