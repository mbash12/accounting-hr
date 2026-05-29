<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_units';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by_user_id) {
                $model->created_by_user_id = Auth::id();
            }

            if (!$model->company_id) {
                $selectedCompanyId = session('selected_company_id');
                if ($selectedCompanyId) {
                    $model->company_id = $selectedCompanyId;
                }
            }
        });
    }

    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'is_purchase_unit',
        'is_sales_unit',
        'company_id',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'product_id' => 'integer',
            'unit_id' => 'integer',
            'conversion_factor' => 'decimal:6',
            'is_purchase_unit' => 'boolean',
            'is_sales_unit' => 'boolean',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
