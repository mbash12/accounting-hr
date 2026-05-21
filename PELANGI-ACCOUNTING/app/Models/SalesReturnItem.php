<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class SalesReturnItem extends Model
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
        'sales_return_id',
        'delivery_document_item_id',
        'product_id',
        'unit_id',
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
            'sales_return_id' => 'integer',
            'delivery_document_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->salesReturn && $model->salesReturn->is_locked) {
                throw ValidationException::withMessages([
                    'items' => 'Cannot add new item to a locked return.',
                ]);
            }
        });

        static::updating(function ($model) {
            if ($model->salesReturn?->getOriginal('is_locked')) {
                $model->delivery_document_item_id = $model->getOriginal('delivery_document_item_id');
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
            if ($model->delivery_document_item_id) {
                $deliveryItem = \App\Models\DeliveryDocumentItem::find($model->delivery_document_item_id);
                if ($deliveryItem) {
                    $totalReturned = self::where('delivery_document_item_id', $model->delivery_document_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('salesReturn', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalReturned + $model->quantity) > $deliveryItem->quantity) {
                        throw ValidationException::withMessages([
                            'quantity' => 'Total return quantity cannot exceed delivery quantity.',
                        ]);
                    }
                }
            }
        });
    }

    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function deliveryDocumentItem(): BelongsTo
    {
        return $this->belongsTo(DeliveryDocumentItem::class);
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
