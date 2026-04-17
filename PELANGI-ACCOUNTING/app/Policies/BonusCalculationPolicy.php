<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BonusCalculation;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonusCalculationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BonusCalculation');
    }

    public function view(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('View:BonusCalculation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BonusCalculation');
    }

    public function update(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('Update:BonusCalculation');
    }

    public function delete(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('Delete:BonusCalculation');
    }

    public function restore(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('Restore:BonusCalculation');
    }

    public function forceDelete(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('ForceDelete:BonusCalculation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BonusCalculation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BonusCalculation');
    }

    public function replicate(AuthUser $authUser, BonusCalculation $bonusCalculation): bool
    {
        return $authUser->can('Replicate:BonusCalculation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BonusCalculation');
    }

}