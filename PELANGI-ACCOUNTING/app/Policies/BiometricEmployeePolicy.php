<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BiometricEmployee;
use Illuminate\Auth\Access\HandlesAuthorization;

class BiometricEmployeePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BiometricEmployee');
    }

    public function view(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('View:BiometricEmployee');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BiometricEmployee');
    }

    public function update(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('Update:BiometricEmployee');
    }

    public function delete(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('Delete:BiometricEmployee');
    }

    public function restore(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('Restore:BiometricEmployee');
    }

    public function forceDelete(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('ForceDelete:BiometricEmployee');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BiometricEmployee');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BiometricEmployee');
    }

    public function replicate(AuthUser $authUser, BiometricEmployee $biometricEmployee): bool
    {
        return $authUser->can('Replicate:BiometricEmployee');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BiometricEmployee');
    }

}