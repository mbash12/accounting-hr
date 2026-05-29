<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class DeliveryDocumentItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'returned_quantity',
        'total_quantity',
        'description',
        'delivery_allocation',
        'delivery_document_id',
        'sales_order_item_id',
        'product_id',
        'unit_id',
        'warehouse_id',
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
            'returned_quantity' => 'decimal:2',
            'total_quantity' => 'string',
            'delivery_allocation' => 'array',
            'delivery_document_id' => 'integer',
            'sales_order_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'warehouse_id' => 'integer',
            'base_quantity' => 'decimal:2',
            'conversion_factor' => 'decimal:6',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->deliveryDocument && $model->deliveryDocument->is_locked) {
                throw ValidationException::withMessages([
                    'items' => 'Cannot add new item to a locked delivery.',
                ]);
            }

            // Set total_quantity to match quantity if not already set and quantity exists
            if (empty($model->total_quantity) && isset($model->quantity)) {
                $model->total_quantity = (string) $model->quantity;
            }
        });

        static::updating(function ($model) {
            if ($model->deliveryDocument?->getOriginal('is_locked')) {
                $model->sales_order_item_id = $model->getOriginal('sales_order_item_id');
                $model->product_id = $model->getOriginal('product_id');
                $model->unit_id = $model->getOriginal('unit_id');
                $model->description = $model->getOriginal('description');

                $originalQty = (float) ($model->getOriginal('quantity') ?? 0);
                $newQty = (float) ($model->quantity ?? 0);
                if ($newQty > $originalQty) {
                    throw ValidationException::withMessages([
                        'items' => 'Quantity cannot be increased on a locked delivery.',
                    ]);
                }
            }

            // Set total_quantity to match quantity if not already set and quantity exists
            if (empty($model->total_quantity) && isset($model->quantity)) {
                $model->total_quantity = (string) $model->quantity;
            }
        });

        static::saving(function ($model) {
            if ($model->sales_order_item_id) {
                $orderItem = \App\Models\SalesOrderItem::find($model->sales_order_item_id);
                if ($orderItem) {
                    $totalDelivered = self::where('sales_order_item_id', $model->sales_order_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('deliveryDocument', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalDelivered + $model->quantity) > $orderItem->quantity) {
                        throw ValidationException::withMessages([
                            'quantity' => 'Total delivery quantity cannot exceed order quantity.',
                        ]);
                    }
                }
            }
        });
    }


    public function deliveryDocument(): BelongsTo
    {
        return $this->belongsTo(DeliveryDocument::class);
    }

    public function salesOrderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class);
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

    public function salesReturnItems(): HasMany
    {
        return $this->hasMany(SalesReturnItem::class);
    }
}
