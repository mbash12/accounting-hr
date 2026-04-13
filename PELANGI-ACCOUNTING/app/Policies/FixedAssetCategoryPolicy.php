<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FixedAssetCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class FixedAssetCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FixedAssetCategory');
    }

    public function view(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('View:FixedAssetCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FixedAssetCategory');
    }

    public function update(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('Update:FixedAssetCategory');
    }

    public function delete(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('Delete:FixedAssetCategory');
    }

    public function restore(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('Restore:FixedAssetCategory');
    }

    public function forceDelete(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('ForceDelete:FixedAssetCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FixedAssetCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FixedAssetCategory');
    }

    public function replicate(AuthUser $authUser, FixedAssetCategory $fixedAssetCategory): bool
    {
        return $authUser->can('Replicate:FixedAssetCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FixedAssetCategory');
    }

}