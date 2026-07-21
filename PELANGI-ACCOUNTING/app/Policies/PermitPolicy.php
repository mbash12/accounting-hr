<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Permit;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Permit');
    }

    public function view(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('View:Permit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Permit');
    }

    public function update(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('Update:Permit');
    }

    public function delete(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('Delete:Permit');
    }

    public function restore(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('Restore:Permit');
    }

    public function forceDelete(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('ForceDelete:Permit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Permit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Permit');
    }

    public function replicate(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('Replicate:Permit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Permit');
    }

    public function approve(AuthUser $authUser, Permit $permit): bool
    {
        return $authUser->can('Approve:Permit');
    }

}