<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PayablePayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayablePaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PayablePayment');
    }

    public function view(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('View:PayablePayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PayablePayment');
    }

    public function update(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('Update:PayablePayment');
    }

    public function delete(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('Delete:PayablePayment');
    }

    public function restore(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('Restore:PayablePayment');
    }

    public function forceDelete(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('ForceDelete:PayablePayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PayablePayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PayablePayment');
    }

    public function replicate(AuthUser $authUser, PayablePayment $payablePayment): bool
    {
        return $authUser->can('Replicate:PayablePayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PayablePayment');
    }

}