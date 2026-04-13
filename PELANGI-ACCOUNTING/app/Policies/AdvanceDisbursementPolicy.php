<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AdvanceDisbursement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvanceDisbursementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AdvanceDisbursement');
    }

    public function view(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('View:AdvanceDisbursement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AdvanceDisbursement');
    }

    public function update(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('Update:AdvanceDisbursement');
    }

    public function delete(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('Delete:AdvanceDisbursement');
    }

    public function restore(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('Restore:AdvanceDisbursement');
    }

    public function forceDelete(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('ForceDelete:AdvanceDisbursement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AdvanceDisbursement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AdvanceDisbursement');
    }

    public function replicate(AuthUser $authUser, AdvanceDisbursement $advanceDisbursement): bool
    {
        return $authUser->can('Replicate:AdvanceDisbursement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AdvanceDisbursement');
    }

}