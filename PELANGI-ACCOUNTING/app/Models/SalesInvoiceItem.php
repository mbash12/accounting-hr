<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->salesInvoice && $model->salesInvoice->is_locked) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => 'Cannot add new item to a locked invoice.',
                ]);
            }
        });

        static::updating(function ($model) {
            if ($model->salesInvoice?->getOriginal('is_locked')) {
                $model->sales_order_item_id = $model->getOriginal('sales_order_item_id');
                $model->product_id = $model->getOriginal('product_id');
                $model->unit_id = $model->getOriginal('unit_id');
                $model->description = $model->getOriginal('description');

                $originalQty = (float) ($model->getOriginal('quantity') ?? 0);
                $newQty = (float) ($model->quantity ?? 0);
                if ($newQty > $originalQty) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => 'Quantity cannot be increased on a locked invoice.',
                    ]);
                }
            }
        });

        static::saving(function ($model) {
            // Calculate total from quantity and unit price
            $model->total = ($model->quantity ?? 0) * ($model->unit_price ?? 0);

            // Validate against order quantity
            if ($model->sales_order_item_id) {
                $orderItem = \App\Models\SalesOrderItem::find($model->sales_order_item_id);
                if ($orderItem) {
                    $totalInvoiced = self::where('sales_order_item_id', $model->sales_order_item_id)
                        ->where('id', '!=', $model->id ?? 0)
                        ->whereHas('salesInvoice', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->sum('quantity');
                    
                    if (($totalInvoiced + $model->quantity) > $orderItem->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'quantity' => 'Total invoice quantity cannot exceed order quantity.',
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
        'discount',
        'discount_percentage',
        'tax_amount',
        'sales_invoice_id',
        'sales_order_item_id',
        'product_id',
        'unit_id',
        'tax_id',
        'cost_center_id',
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
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
            'discount' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'sales_invoice_id' => 'integer',
            'sales_order_item_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'tax_id' => 'integer',
            'cost_center_id' => 'integer',
            'base_quantity' => 'decimal:2',
            'conversion_factor' => 'decimal:6',
        ];
    }

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class);
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

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}
