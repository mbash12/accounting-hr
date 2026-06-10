<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReceivableContact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceivableContactPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReceivableContact');
    }

    public function view(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('View:ReceivableContact');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReceivableContact');
    }

    public function update(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('Update:ReceivableContact');
    }

    public function delete(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('Delete:ReceivableContact');
    }

    public function restore(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('Restore:ReceivableContact');
    }

    public function forceDelete(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('ForceDelete:ReceivableContact');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReceivableContact');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReceivableContact');
    }

    public function replicate(AuthUser $authUser, ReceivableContact $receivableContact): bool
    {
        return $authUser->can('Replicate:ReceivableContact');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReceivableContact');
    }

}