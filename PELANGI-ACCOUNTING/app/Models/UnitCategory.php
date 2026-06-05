<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UnitCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit_categories';

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

        static::saved(function ($model) {
            // Ensure the base unit's conversion_factor is always 1
            if ($model->wasChanged('base_unit_id') || $model->wasRecentlyCreated) {
                Unit::where('id', $model->base_unit_id)
                    ->where('conversion_factor', '!=', 1)
                    ->update(['conversion_factor' => 1]);
            }
        });
    }

    protected $fillable = [
        'name',
        'base_unit_id',
        'company_id',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'base_unit_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
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
