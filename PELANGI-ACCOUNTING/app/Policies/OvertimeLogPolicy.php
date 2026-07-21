<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OvertimeLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class OvertimeLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OvertimeLog');
    }

    public function view(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('View:OvertimeLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OvertimeLog');
    }

    public function update(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('Update:OvertimeLog');
    }

    public function delete(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('Delete:OvertimeLog');
    }

    public function restore(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('Restore:OvertimeLog');
    }

    public function forceDelete(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('ForceDelete:OvertimeLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OvertimeLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OvertimeLog');
    }

    public function replicate(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('Replicate:OvertimeLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OvertimeLog');
    }

    public function approve(AuthUser $authUser, OvertimeLog $overtimeLog): bool
    {
        return $authUser->can('Approve:OvertimeLog');
    }

}