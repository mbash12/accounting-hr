<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ShiftType extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by_user_id) {
                $model->created_by_user_id = Auth::id();
            }
            if (!$model->company_id) {
                $selectedCompanyId = session('selected_company_id');
                if ($selectedCompanyId && $selectedCompanyId !== 'all') {
                    $model->company_id = $selectedCompanyId;
                }
            }
        });
    }

    protected $fillable = [
        'company_id', 'code', 'name',
        'start_time', 'end_time',
        'color', 'text_color',
        'is_off', 'is_active', 'description',
    ];

    protected $casts = [
        'is_off'    => 'boolean',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ShiftSchedule::class);
    }
}
