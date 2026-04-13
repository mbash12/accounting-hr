<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TransactionClassification;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionClassificationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TransactionClassification');
    }

    public function view(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('View:TransactionClassification');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TransactionClassification');
    }

    public function update(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('Update:TransactionClassification');
    }

    public function delete(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('Delete:TransactionClassification');
    }

    public function restore(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('Restore:TransactionClassification');
    }

    public function forceDelete(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('ForceDelete:TransactionClassification');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TransactionClassification');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TransactionClassification');
    }

    public function replicate(AuthUser $authUser, TransactionClassification $transactionClassification): bool
    {
        return $authUser->can('Replicate:TransactionClassification');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TransactionClassification');
    }

}