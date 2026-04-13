<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PurchaseInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseInvoicePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PurchaseInvoice');
    }

    public function view(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('View:PurchaseInvoice');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PurchaseInvoice');
    }

    public function update(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('Update:PurchaseInvoice');
    }

    public function delete(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('Delete:PurchaseInvoice');
    }

    public function restore(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('Restore:PurchaseInvoice');
    }

    public function forceDelete(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('ForceDelete:PurchaseInvoice');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PurchaseInvoice');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PurchaseInvoice');
    }

    public function replicate(AuthUser $authUser, PurchaseInvoice $purchaseInvoice): bool
    {
        return $authUser->can('Replicate:PurchaseInvoice');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PurchaseInvoice');
    }

}