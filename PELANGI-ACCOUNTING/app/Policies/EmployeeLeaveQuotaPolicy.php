<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeLeaveQuotaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmployeeLeaveQuota');
    }

    public function view(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('View:EmployeeLeaveQuota');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmployeeLeaveQuota');
    }

    public function update(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('Update:EmployeeLeaveQuota');
    }

    public function delete(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('Delete:EmployeeLeaveQuota');
    }

    public function restore(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('Restore:EmployeeLeaveQuota');
    }

    public function forceDelete(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('ForceDelete:EmployeeLeaveQuota');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmployeeLeaveQuota');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmployeeLeaveQuota');
    }

    public function replicate(AuthUser $authUser, EmployeeLeaveQuota $employeeLeaveQuota): bool
    {
        return $authUser->can('Replicate:EmployeeLeaveQuota');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmployeeLeaveQuota');
    }

}