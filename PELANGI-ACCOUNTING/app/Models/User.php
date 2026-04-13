<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasCompanyFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
        'companies',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the companies associated with the user.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_companies', 'user_id', 'company_id')
            ->withPivot('assigned_by_user_id')
            ->withTimestamps();
    }

    /**
     * Get the user company assignments.
     */
    public function userCompanies()
    {
        return $this->hasMany(UserCompany::class);
    }



    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            // Handle company assignments when user is saved
            if (request()->has('companies')) {
                $companies = request()->get('companies');

                if (!empty($companies) && is_array($companies)) {
                    // Use authenticated user ID to track who assigned the company
                    $assignedByUserId = auth()->id();
                    if (!$assignedByUserId) {
                        // If no authenticated user (e.g., during seeding or CLI), use the current user
                        $assignedByUserId = $user->id ?? 1;
                    }

                    // Sync companies with assigned_by_user_id
                    $user->companies()->syncWithPivotValues($companies, [
                        'assigned_by_user_id' => $assignedByUserId
                    ], false); // false means don't touch timestamps
                } else {
                    // If no companies selected, detach all
                    $user->companies()->detach();
                }
            }
        });
    }
}
