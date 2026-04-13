<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PayrollPeriod extends Model
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
    }

    protected $fillable = [
        'name',
        'month',
        'year',
        'start_date',
        'end_date',
        'apply_attendance_deduction',
        'status',
        'total_gross_salary',
        'total_deductions',
        'total_net_salary',
        'total_pph21',
        'total_bpjs_employer',
        'total_bpjs_employee',
        'journal_entry_id',
        'company_id',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'apply_attendance_deduction' => 'boolean',
            'total_gross_salary' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_net_salary' => 'decimal:2',
            'total_pph21' => 'decimal:2',
            'total_bpjs_employer' => 'decimal:2',
            'total_bpjs_employee' => 'decimal:2',
            'journal_entry_id' => 'integer',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
