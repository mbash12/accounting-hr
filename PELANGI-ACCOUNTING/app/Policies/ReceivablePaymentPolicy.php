<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReceivablePayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceivablePaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReceivablePayment');
    }

    public function view(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('View:ReceivablePayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReceivablePayment');
    }

    public function update(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('Update:ReceivablePayment');
    }

    public function delete(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('Delete:ReceivablePayment');
    }

    public function restore(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('Restore:ReceivablePayment');
    }

    public function forceDelete(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('ForceDelete:ReceivablePayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReceivablePayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReceivablePayment');
    }

    public function replicate(AuthUser $authUser, ReceivablePayment $receivablePayment): bool
    {
        return $authUser->can('Replicate:ReceivablePayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReceivablePayment');
    }

}