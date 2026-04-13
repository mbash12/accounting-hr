<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CashDisbursement;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashDisbursementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CashDisbursement');
    }

    public function view(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('View:CashDisbursement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CashDisbursement');
    }

    public function update(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('Update:CashDisbursement');
    }

    public function delete(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('Delete:CashDisbursement');
    }

    public function restore(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('Restore:CashDisbursement');
    }

    public function forceDelete(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('ForceDelete:CashDisbursement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CashDisbursement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CashDisbursement');
    }

    public function replicate(AuthUser $authUser, CashDisbursement $cashDisbursement): bool
    {
        return $authUser->can('Replicate:CashDisbursement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CashDisbursement');
    }

}