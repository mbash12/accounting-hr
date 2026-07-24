<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ShiftType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShiftType');
    }

    public function view(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('View:ShiftType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShiftType');
    }

    public function update(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('Update:ShiftType');
    }

    public function delete(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('Delete:ShiftType');
    }

    public function restore(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('Restore:ShiftType');
    }

    public function forceDelete(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('ForceDelete:ShiftType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShiftType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShiftType');
    }

    public function replicate(AuthUser $authUser, ShiftType $shiftType): bool
    {
        return $authUser->can('Replicate:ShiftType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShiftType');
    }

}