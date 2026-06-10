<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnitCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnitCategory');
    }

    public function view(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('View:UnitCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnitCategory');
    }

    public function update(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('Update:UnitCategory');
    }

    public function delete(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('Delete:UnitCategory');
    }

    public function restore(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('Restore:UnitCategory');
    }

    public function forceDelete(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('ForceDelete:UnitCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnitCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnitCategory');
    }

    public function replicate(AuthUser $authUser, UnitCategory $unitCategory): bool
    {
        return $authUser->can('Replicate:UnitCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnitCategory');
    }

}