<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class THRCalculationItem extends Model
{
    use HasFactory;

    protected $table = 'thr_calculation_items';

    protected $fillable = [
        'thr_calculation_id',
        'employee_id',
        'basic_salary',
        'months_service',
        'amount',
        'pph21',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'thr_calculation_id' => 'integer',
            'employee_id' => 'integer',
            'basic_salary' => 'decimal:2',
            'months_service' => 'integer',
            'amount' => 'decimal:2',
            'pph21' => 'decimal:2',
            'company_id' => 'integer',
        ];
    }

    public function thrCalculation(): BelongsTo
    {
        return $this->belongsTo(THRCalculation::class, 'thr_calculation_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
