<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DeferredRevenue;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeferredRevenuePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DeferredRevenue');
    }

    public function view(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('View:DeferredRevenue');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DeferredRevenue');
    }

    public function update(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('Update:DeferredRevenue');
    }

    public function delete(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('Delete:DeferredRevenue');
    }

    public function restore(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('Restore:DeferredRevenue');
    }

    public function forceDelete(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('ForceDelete:DeferredRevenue');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DeferredRevenue');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DeferredRevenue');
    }

    public function replicate(AuthUser $authUser, DeferredRevenue $deferredRevenue): bool
    {
        return $authUser->can('Replicate:DeferredRevenue');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DeferredRevenue');
    }

}