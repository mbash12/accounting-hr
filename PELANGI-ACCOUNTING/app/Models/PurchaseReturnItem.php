<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class PurchaseReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'description',
        'return_reason',
        'purchase_return_id',
        'goods_receipt_item_id',
        'product_id',
        'unit_id',
        'base_quantity',
        'conversion_factor',
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
            'purchase_return_id' => 'integer',
            'goods_receipt_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'base_quantity' => 'decimal:2',
            'conversion_factor' => 'decimal:6',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->purchaseReturn && $model->purchaseReturn->is_locked) {
                throw ValidationException::withMessages([
                    'items' => 'Cannot add new item to a locked return.',
                ]);
            }
        });

        static::updating(function ($model) {
            if ($model->purchaseReturn?->getOriginal('is_locked')) {
                $model->goods_receipt_item_id = $model->getOriginal('goods_receipt_item_id');
                $model->product_id = $model->getOriginal('product_id');
                $model->unit_id = $model->getOriginal('unit_id');
                $model->description = $model->getOriginal('description');

                $originalQty = (float) ($model->getOriginal('quantity') ?? 0);
                $newQty = (float) ($model->quantity ?? 0);
                if ($newQty > $originalQty) {
                    throw ValidationException::withMessages([
                        'items' => 'Quantity cannot be increased on a locked return.',
                    ]);
                }
            }
        });

        static::saving(function ($model) {
            if ($model->goods_receipt_item_id) {
                $receiptItem = \App\Models\GoodsReceiptItem::find($model->goods_receipt_item_id);
                if ($receiptItem) {
                    $totalReturned = self::where('goods_receipt_item_id', $model->goods_receipt_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('purchaseReturn', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalReturned + $model->quantity) > $receiptItem->quantity) {
                        throw ValidationException::withMessages([
                            'quantity' => 'Total return quantity cannot exceed receipt quantity.',
                        ]);
                    }
                }
            }
        });
    }

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function goodsReceiptItem(): BelongsTo
    {
        return $this->belongsTo(GoodsReceiptItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
