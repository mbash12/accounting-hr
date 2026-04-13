<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JournalEntryItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalEntryItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JournalEntryItem');
    }

    public function view(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('View:JournalEntryItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JournalEntryItem');
    }

    public function update(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('Update:JournalEntryItem');
    }

    public function delete(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('Delete:JournalEntryItem');
    }

    public function restore(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('Restore:JournalEntryItem');
    }

    public function forceDelete(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('ForceDelete:JournalEntryItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JournalEntryItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JournalEntryItem');
    }

    public function replicate(AuthUser $authUser, JournalEntryItem $journalEntryItem): bool
    {
        return $authUser->can('Replicate:JournalEntryItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JournalEntryItem');
    }

}