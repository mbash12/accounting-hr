<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class OvertimeLog extends Model
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
                if ($selectedCompanyId) {
                    $model->company_id = $selectedCompanyId;
                }
            }
        });

        // Always recalculate hours from time_start/time_end before saving
        static::saving(function ($model) {
            if ($model->time_start && $model->time_end) {
                $start = \Carbon\Carbon::parse($model->time_start);
                $end   = \Carbon\Carbon::parse($model->time_end);
                $mins  = abs($end->diffInMinutes($start));
                $model->hours = max(0.5, round($mins / 60, 2));
            }
        });
    }

    protected $fillable = [
        'employee_id',
        'date',
        'time_start',
        'time_end',
        'hours',
        'is_holiday',
        'calculated_amount',
        'status',
        'reason',
        'approved_by_user_id',
        'company_id',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'time_start' => 'datetime:H:i',
            'time_end' => 'datetime:H:i',
            'hours' => 'decimal:2',
            'is_holiday' => 'boolean',
            'calculated_amount' => 'decimal:2',
            'employee_id' => 'integer',
            'approved_by_user_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Determine if a date is a holiday for the given employee.
     *
     * Checks:
     *  1. Company holidays table
     *  2. Department's working_days (weekend detection)
     *  3. Default: Saturday/Sunday
     *
     * @param int|null $employeeId
     * @param string|null $date Y-m-d format or Carbon instance
     * @return bool
     */
    public static function isHoliday(?int $employeeId, $date): bool
    {
        if (! $employeeId || ! $date) {
            return false;
        }

        $employee = Employee::with('department')->find($employeeId);
        if (! $employee) {
            return false;
        }

        $date = \Carbon\Carbon::parse($date);

        // 1. Check company holidays table
        $isCompanyHoliday = Holiday::where('company_id', $employee->company_id)
            ->whereDate('date', $date)
            ->exists();

        if ($isCompanyHoliday) {
            return true;
        }

        // 2. Check weekend based on department's working_days
        if ($employee->department && filled($employee->department->working_days)) {
            $dayName = $date->format('l'); // e.g., 'Saturday', 'Sunday'

            return ! in_array($dayName, $employee->department->working_days);
        }

        // Default: Saturday and Sunday are holidays
        return $date->isWeekend();
    }
}
