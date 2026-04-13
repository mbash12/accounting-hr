<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceiptItem extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->goodsReceipt && $model->goodsReceipt->is_locked) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => 'Tidak dapat menambah item baru pada penerimaan yang terkunci.',
                ]);
            }
        });

        static::updating(function ($model) {
            if ($model->goodsReceipt?->getOriginal('is_locked')) {
                $model->purchase_order_item_id = $model->getOriginal('purchase_order_item_id');
                $model->product_id = $model->getOriginal('product_id');
                $model->unit_id = $model->getOriginal('unit_id');
                $model->description = $model->getOriginal('description');

                $originalQty = (float) ($model->getOriginal('quantity') ?? 0);
                $newQty = (float) ($model->quantity ?? 0);
                if ($newQty > $originalQty) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => 'Kuantitas tidak boleh ditambah pada penerimaan yang terkunci.',
                    ]);
                }
            }
        });

        static::saving(function ($model) {
            if ($model->purchase_order_item_id) {
                $orderItem = \App\Models\PurchaseOrderItem::find($model->purchase_order_item_id);
                if ($orderItem) {
                    $totalReceived = self::where('purchase_order_item_id', $model->purchase_order_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('goodsReceipt', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalReceived + $model->quantity) > $orderItem->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'quantity' => 'Total kuantitas penerimaan tidak boleh melebihi kuantitas pesanan.',
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
        'returned_quantity',
        'description',
        'batch_number',
        'expiry_date',
        'unit_cost',
        'goods_receipt_id',
        'purchase_order_item_id',
        'product_id',
        'item_name',
        'unit_id',
        'warehouse_id',
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
            'returned_quantity' => 'decimal:2',
            'expiry_date' => 'date',
            'goods_receipt_id' => 'integer',
            'purchase_order_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'warehouse_id' => 'integer',
        ];
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
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

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
