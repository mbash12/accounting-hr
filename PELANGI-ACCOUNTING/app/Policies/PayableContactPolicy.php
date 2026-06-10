<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PayableContact;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayableContactPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PayableContact');
    }

    public function view(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('View:PayableContact');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PayableContact');
    }

    public function update(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('Update:PayableContact');
    }

    public function delete(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('Delete:PayableContact');
    }

    public function restore(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('Restore:PayableContact');
    }

    public function forceDelete(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('ForceDelete:PayableContact');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PayableContact');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PayableContact');
    }

    public function replicate(AuthUser $authUser, PayableContact $payableContact): bool
    {
        return $authUser->can('Replicate:PayableContact');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PayableContact');
    }

}