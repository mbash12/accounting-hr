<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AttendanceSpot;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceSpotPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AttendanceSpot');
    }

    public function view(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('View:AttendanceSpot');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AttendanceSpot');
    }

    public function update(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('Update:AttendanceSpot');
    }

    public function delete(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('Delete:AttendanceSpot');
    }

    public function restore(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('Restore:AttendanceSpot');
    }

    public function forceDelete(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('ForceDelete:AttendanceSpot');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AttendanceSpot');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AttendanceSpot');
    }

    public function replicate(AuthUser $authUser, AttendanceSpot $attendanceSpot): bool
    {
        return $authUser->can('Replicate:AttendanceSpot');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AttendanceSpot');
    }

}