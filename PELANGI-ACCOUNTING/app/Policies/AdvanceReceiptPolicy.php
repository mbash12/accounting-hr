<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AdvanceReceipt;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvanceReceiptPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AdvanceReceipt');
    }

    public function view(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('View:AdvanceReceipt');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AdvanceReceipt');
    }

    public function update(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('Update:AdvanceReceipt');
    }

    public function delete(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('Delete:AdvanceReceipt');
    }

    public function restore(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('Restore:AdvanceReceipt');
    }

    public function forceDelete(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('ForceDelete:AdvanceReceipt');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AdvanceReceipt');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AdvanceReceipt');
    }

    public function replicate(AuthUser $authUser, AdvanceReceipt $advanceReceipt): bool
    {
        return $authUser->can('Replicate:AdvanceReceipt');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AdvanceReceipt');
    }

}