<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiometricEmployee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'machine_user_id',
        'name',
        'company_id',
        'employee_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'machine_user_id' => 'integer',
            'employee_id' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
