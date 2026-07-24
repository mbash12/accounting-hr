<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ShiftSchedule extends Model
{
    use HasFactory, SoftDeletes;

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
        'company_id', 'employee_id', 'shift_type_id',
        'date', 'shift_code',
        'is_off', 'is_holiday', 'holiday_name', 'is_leave', 'notes',
    ];

    protected $casts = [
        'date'      => 'date',
        'is_off'    => 'boolean',
        'is_holiday'=> 'boolean',
        'is_leave'  => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftType(): BelongsTo
    {
        return $this->belongsTo(ShiftType::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
