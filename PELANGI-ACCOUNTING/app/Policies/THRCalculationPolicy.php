<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\THRCalculation;
use Illuminate\Auth\Access\HandlesAuthorization;

class THRCalculationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:THRCalculation');
    }

    public function view(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('View:THRCalculation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:THRCalculation');
    }

    public function update(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('Update:THRCalculation');
    }

    public function delete(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('Delete:THRCalculation');
    }

    public function restore(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('Restore:THRCalculation');
    }

    public function forceDelete(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('ForceDelete:THRCalculation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:THRCalculation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:THRCalculation');
    }

    public function replicate(AuthUser $authUser, THRCalculation $tHRCalculation): bool
    {
        return $authUser->can('Replicate:THRCalculation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:THRCalculation');
    }

}