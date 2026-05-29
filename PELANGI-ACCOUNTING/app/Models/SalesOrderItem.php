<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Always calculate total based on current quantity and unit_price
            $quantity = $model->quantity ?? 0;
            $unitPrice = $model->unit_price ?? 0;
            $model->total = $quantity * $unitPrice;
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
        'delivered_quantity',
        'invoiced_quantity',
        'sales_order_id',
        'product_id',
        'unit_id',
        'tax_id',
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
            'delivered_quantity' => 'decimal:2',
            'invoiced_quantity' => 'decimal:2',
            'sales_order_id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'tax_id' => 'integer',
            'base_quantity' => 'decimal:2',
            'conversion_factor' => 'decimal:6',
        ];
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
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

    public function deliveryDocumentItems(): HasMany
    {
        return $this->hasMany(DeliveryDocumentItem::class);
    }

    public function salesInvoiceItems(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }
}
