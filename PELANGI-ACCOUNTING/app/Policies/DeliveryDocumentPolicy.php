<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DeliveryDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryDocumentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DeliveryDocument');
    }

    public function view(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('View:DeliveryDocument');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DeliveryDocument');
    }

    public function update(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('Update:DeliveryDocument');
    }

    public function delete(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('Delete:DeliveryDocument');
    }

    public function restore(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('Restore:DeliveryDocument');
    }

    public function forceDelete(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('ForceDelete:DeliveryDocument');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DeliveryDocument');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DeliveryDocument');
    }

    public function replicate(AuthUser $authUser, DeliveryDocument $deliveryDocument): bool
    {
        return $authUser->can('Replicate:DeliveryDocument');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DeliveryDocument');
    }

}