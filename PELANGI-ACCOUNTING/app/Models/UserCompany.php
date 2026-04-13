<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompany extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'assigned_by_user_id',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($userCompany) {
            // Ensure assigned_by_user_id is set when creating a new record
            if (is_null($userCompany->assigned_by_user_id) || $userCompany->assigned_by_user_id === '') {
                if (auth()->check()) {
                    // Use current authenticated user as the assigner
                    $userCompany->assigned_by_user_id = auth()->id();
                } elseif (!empty($userCompany->user_id)) {
                    // If no authenticated user but user_id is set, assign to the user themselves by default
                    $userCompany->assigned_by_user_id = $userCompany->user_id;
                } else {
                    // Last resort: try to use the first user in the system
                    // This should rarely happen in a real application
                    $userCompany->assigned_by_user_id = 1;
                }
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'company_id' => 'integer',
            'assigned_by_user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
