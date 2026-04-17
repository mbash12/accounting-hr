<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalaryComponent;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryComponentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalaryComponent');
    }

    public function view(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('View:SalaryComponent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalaryComponent');
    }

    public function update(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('Update:SalaryComponent');
    }

    public function delete(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('Delete:SalaryComponent');
    }

    public function restore(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('Restore:SalaryComponent');
    }

    public function forceDelete(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('ForceDelete:SalaryComponent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalaryComponent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalaryComponent');
    }

    public function replicate(AuthUser $authUser, SalaryComponent $salaryComponent): bool
    {
        return $authUser->can('Replicate:SalaryComponent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalaryComponent');
    }

}