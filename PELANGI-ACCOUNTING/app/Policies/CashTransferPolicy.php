<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CashTransfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashTransferPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CashTransfer');
    }

    public function view(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('View:CashTransfer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CashTransfer');
    }

    public function update(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('Update:CashTransfer');
    }

    public function delete(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('Delete:CashTransfer');
    }

    public function restore(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('Restore:CashTransfer');
    }

    public function forceDelete(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('ForceDelete:CashTransfer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CashTransfer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CashTransfer');
    }

    public function replicate(AuthUser $authUser, CashTransfer $cashTransfer): bool
    {
        return $authUser->can('Replicate:CashTransfer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CashTransfer');
    }

}