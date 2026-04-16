<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusCalculationItem extends Model
{
    use HasFactory;

    protected $table = 'bonus_calculation_items';

    protected $fillable = [
        'bonus_calculation_id',
        'employee_id',
        'amount',
        'pph21',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'bonus_calculation_id' => 'integer',
            'employee_id' => 'integer',
            'amount' => 'decimal:2',
            'pph21' => 'decimal:2',
            'company_id' => 'integer',
        ];
    }

    public function bonusCalculation(): BelongsTo
    {
        return $this->belongsTo(BonusCalculation::class, 'bonus_calculation_id');
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
